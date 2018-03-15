<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchBill */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div class="bill-index">

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'bid',
            'table',
            'timestamp',
            'payment_mode',
            'discount',
            'gst',
            'tax',
            'amount',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
