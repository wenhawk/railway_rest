<?php

namespace app\controllers;

use Yii;
use app\models\Kot;
use app\models\Orders;
use app\models\RTable;
use app\models\Waiter;
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
        $waiter = new Waiter();
        $table = RTable::findOne($tid);

        if ($order->load(Yii::$app->request->post()) && $waiter->load(Yii::$app->request->post())) {
             $kot = Kot::createKot($order,$waiter);
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
                'item' => $item,
                'waiter' => $waiter
            ]);
        }
    }

    public function actionView($id)
    {
      $kot = Kot::findOne($id);
      $waiter = Waiter::findOne($kot->wid);
      $orders = $kot->getAllOrders();
        return $this->render('view_kot',[
          'orders' => $orders,
          'kot' => $kot,
          'waiter' => $waiter,
        ]);
    }

    public function actionUpdate($id)
    {
        $kot = $this->findModel($id);
        $waiter = Waiter::findOne($kot->wid);
        $waiterModel = new Waiter();
        $orders = $kot->getAllOrders();
          $formOrders = new Orders();
          if ($formOrders->load(Yii::$app->request->post()) && $waiterModel->load(Yii::$app->request->post())) {
            $orderArray = Orders::updateOrders($formOrders, $orders);
            $kot->wid = $waiterModel->name;
            $kot->save();
            try{
              Kot::printKot($kot, $orderArray,$waiter);
              }
            catch(yii\Base\ErrorException $e) {
               Yii::$app->session->setFlash('danger', "PRINTER NOT CONNECTED");
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
                  'kot' => $kot,
                  'waiter' => $waiter,
              ]);
          }
    }

    public function actionPrint($kid)
    {
        $kot = $this->findModel($kid);
        $waiter = Waiter::findOne($kot->wid);
        $orders = $kot->getAllOrders();
        try{
          Kot::printKot($kot, $orders,$waiter);
          }
          catch(yii\Base\ErrorException $e) {
             Yii::$app->session->setFlash('danger', "PRINTER NOT CONNECTED");
          }
        return $this->redirect(['site/index']);
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
