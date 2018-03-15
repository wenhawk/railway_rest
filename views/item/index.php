<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchItem */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">
    <p>
        <?= Html::a('Create Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'iid',
            'name',
            'cost',
            ['class' => 'yii\grid\ActionColumn',
            'visibleButtons' => [
                'view' => Yii::$app->user->can('update'),
                'delete' => Yii::$app->user->can('update')
            ]]
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
