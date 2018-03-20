<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\RTable;
use app\models\Bill;
use app\models\Report;
use app\models\Orders;
use app\models\Git;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        $tables = RTable::find()->where(['flag' => 'true'])->all();
        Git::pull();
        // return $this->render('index',[
        //     'tables' => $tables
        // ]);
    }

    public function actionReport() {
      $model = new Report();
      if($model->load(Yii::$app->request->post())) {
        $startDate = $model->startDate;
        if($model->endDate){
          $endDate = $model->endDate;
        }
        else{
          $endDate = $startDate;
        }
        $startDate = $startDate.' 00:00:00';
        $endDate = $endDate.' 23:59:59';
        $total =  Bill::calculateBillTotal($startDate,$endDate,'amount');
        $tax =  Bill::calculateBillTotal($startDate,$endDate,'tax');
        $cash = Bill::calculateBillTotalWithPaymentMode($startDate,$endDate,'cash');
        $card = Bill::calculateBillTotalWithPaymentMode($startDate,$endDate,'card');
        $credit = Bill::calculateBillTotalWithPaymentMode($startDate,$endDate,'credit');
        $orders = Orders::getAllOrders($startDate,$endDate);
        return $this->render('viewReport',[
          'total' => $total,
          'cash' => $cash,
          'card' => $card,
          'credit' => $credit,
          'orders' => $orders,
          'tax' => $tax
        ]);
      }
      else{
        return $this->render('report');
      }
    }



}
