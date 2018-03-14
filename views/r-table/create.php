<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RTable */

$this->title = 'Create Rtable';
$this->params['breadcrumbs'][] = ['label' => 'Rtables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rtable-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
