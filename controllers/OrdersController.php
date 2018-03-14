<?php

namespace app\controllers;

use Yii;
use app\models\Orders;
use app\models\SearchOrders;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class OrdersController extends Controller
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
        $searchModel = new SearchOrders();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($tid, $iid, $kid, $rank)
    {
        return $this->render('view', [
            'model' => $this->findModel($tid, $iid, $kid, $rank),
        ]);
    }

    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'tid' => $model->tid, 'iid' => $model->iid, 'kid' => $model->kid, 'rank' => $model->rank]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($tid, $iid, $kid, $rank)
    {
        $model = $this->findModel($tid, $iid, $kid, $rank);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'tid' => $model->tid, 'iid' => $model->iid, 'kid' => $model->kid, 'rank' => $model->rank]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($tid, $iid, $kid, $rank)
    {
        $this->findModel($tid, $iid, $kid, $rank)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($tid, $iid, $kid, $rank)
    {
        if (($model = Orders::findOne(['tid' => $tid, 'iid' => $iid, 'kid' => $kid, 'rank' => $rank])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
