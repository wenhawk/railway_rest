<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bill-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group field-bill-payment_mode required">
<label class="control-label" for="bill-payment_mode">Payment Mode</label>
<select id="bill-payment_mode" class="form-control" name="Bill[payment_mode]" aria-required="true">
<option value="<?= $model->payment_mode ?>"><?=$model->payment_mode?></option>
<option value="card">Card</option>
<option value="card">Cash</option>
<option value="credit">Credit</option>
</select>

    <?= $form->field($model, 'discount')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
