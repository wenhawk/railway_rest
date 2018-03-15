<?php

namespace app\controllers;

use Yii;
use app\models\Kot;
use app\models\Orders;
use app\models\RTable;
use app\models\Item;
use app\models\SearchKot;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class KotController extends Controller
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

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleteAllOrders();
        $model->flag = 'false';
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionIndex()
    {
        $searchModel = new SearchKot();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate($tid)
    {
        $order = new Orders();
        $item = new Item();
        $table = RTable::findOne($tid);

        if ($order->load(Yii::$app->request->post())) {
             $kot = Kot::createKot($order);
             $orders = $kot->getAllOrders();
             try{
               Kot::printKot($kot->kid, $orders);
             }
             catch(yii\Base\ErrorException $e) {
                // print not conected
             }
             return $this->redirect(['view', 'id' => $kot->kid]);
        } else {
            return $this->render('create', [
                'order' => $order,
                'table' => $table,
                'item' => $item
            ]);
        }
    }

    public function actionView($id)
    {
      $kot = Kot::findOne($id);
      $orders = $kot->getAllOrders();
        return $this->render('view_kot',[
          'orders' => $orders,
          'kot' => $kot
        ]);
    }

    public function actionUpdate($id)
    {
        $kot = $this->findModel($id);
        $orders = $kot->getAllOrders();
          $formOrders = new Orders();
          if ($formOrders->load(Yii::$app->request->post())) {
            $orderArray = Orders::updateOrders($formOrders, $orders);
            try{
              Kot::printKot($kot, $orderArray);
              }
            catch(yii\Base\ErrorException $e) {
               // print not conected
            }
            if($kot->isEmpty()){
              $kot->deleteAllOrders();
              $kot->flag = 'false';
              $kot->save();
            }
            return $this->redirect(['site/index']);
          } else {
              return $this->render('edit_kot', [
                  'orders' => $orders,
                  'kot' => $kot
              ]);
          }
    }

    protected function findModel($id)
    {
        if (($model = Kot::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
