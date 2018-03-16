<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchTax */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Taxes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tax-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tax', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'value',
            ['class' => 'yii\grid\ActionColumn',
            'visibleButtons' => [
                'view' => Yii::$app->user->can('update'),
                'delete' => Yii::$app->user->can('update')
            ]]
        ],
    ]); ?>
</div>
