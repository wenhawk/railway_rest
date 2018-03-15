<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

?>
<div class="kot-index">

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'kid',
            'timestamp',
            ['class' => 'yii\grid\ActionColumn',
            'visibleButtons' => [
                'view' => Yii::$app->user->can('update'),
                'update' => Yii::$app->user->can('update'),
                'delete' => Yii::$app->user->can('update')
            ]]
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
