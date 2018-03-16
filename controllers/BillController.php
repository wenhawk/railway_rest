<?php

namespace app\controllers;

use Yii;
use app\models\Bill;
use app\models\BillKot;
use app\models\Kot;
use app\models\RTable;
use app\models\Tax;
use app\models\Orders;
use app\models\SearchBill;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class BillController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new SearchBill();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $bill = Bill::findOne($id);
        $orders = Orders::find()->where(['in','orders.kid',BillKot::find()
                               ->where(['bid'=>$id])->select('kid')])
                               ->andWhere(['flag'=>'true'])
                               ->orderBy(['iid'=>SORT_DESC])
                               ->all();
        $orders = Orders::mergeIdenticalOrders($orders);
        $table = RTable::findOne($orders[0]->tid);
        return $this->render('view', [
            'discount' => $bill->discount_amount,
            'tax' => $bill->tax,
            'amount' => $bill->amount,
            'bill' => $bill,
            'orders' => $orders,
            'table' => $table,
            'total_amount' => $bill->total_amount
        ]);
    }

    public function actionCreate($tid)
    {
        $bill = new Bill();
        $table = RTable::findOne($tid);
        $orders = $table->getOrdersNotBilled()->joinWith('item')->all();
        if($orders){
          $orders = Orders::mergeIdenticalOrders($orders);
          $tax = Tax::find()->one();
          $amount = $table->calculateBillTotal();
          $total_amount = round($amount + ( $amount * ($tax->value/100)));
          if ($bill->load(Yii::$app->request->post())) {
              $bill->generateBill($table, $amount);
              //TODO print code
              return $this->redirect(['site/index']);
          } else {
              return $this->render('create', [
                  'bill' => $bill,
                  'orders' => $orders,
                  'table' => $table,
                  'amount' => $total_amount
              ]);
          }
        }
        else{
            return $this->redirect(['site/index']);
        }

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->total_amount = $model->amount + $model->tax;
            $model->discount_amount = round($model->total_amount * ($model->discount/100));
            $model->total_amount = round($model->total_amount - $model->discount_amount);
            $model->save();
            return $this->redirect(['bill/view','id'=>$id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionPrint($id)
    {
      $bill = Bill::findOne($id);
      $orders = Orders::find()->where(['in','orders.kid',BillKot::find()
                             ->where(['bid'=>$id])->select('kid')])
                             ->andWhere(['flag'=>'true'])
                             ->orderBy(['iid'=>SORT_DESC])
                             ->all();
      $orders = Orders::mergeIdenticalOrders($orders);
      $table = RTable::findOne($orders[0]->tid);
      //TODO print code
      return $this->redirect(['site/index']);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $billKots = $model->billKots;
        foreach ($billKots as $billKot) {
          $kot = Kot::findOne($billKot->kid);
          $orders = $kot->orders;
            foreach ($orders as $order) {
              $order->delete();
            }
          $billKot->delete();
          $kot->delete();
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Bill::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
