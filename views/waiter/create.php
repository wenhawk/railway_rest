<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Waiter */

$this->title = 'Create Waiter';
$this->params['breadcrumbs'][] = ['label' => 'Waiters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waiter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
