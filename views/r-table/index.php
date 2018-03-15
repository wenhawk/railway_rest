<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchRTable */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rtable-index">

    <p>
        <?= Html::a('Create Table', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
      <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
          if($model->flag == 'false'){
            return ['class' => 'danger'];
          }
        },
        'columns' => [
            'tid',
            'name',
            ['class' => 'yii\grid\ActionColumn',
            'visibleButtons' => [
                'view' => Yii::$app->user->can('update')
            ]]
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
