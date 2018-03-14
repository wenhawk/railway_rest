<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = $model->tid;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'tid' => $model->tid, 'iid' => $model->iid, 'kid' => $model->kid, 'rank' => $model->rank], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'tid' => $model->tid, 'iid' => $model->iid, 'kid' => $model->kid, 'rank' => $model->rank], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tid',
            'iid',
            'kid',
            'quantity',
            'flag',
            'status',
            'rank',
            'message',
        ],
    ]) ?>

</div>
