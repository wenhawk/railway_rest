<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchRTable */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rtables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rtable-index">

    <p>
        <?= Html::a('Create Table', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tid',
            'name',
            'flag',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
