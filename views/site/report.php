<?php

use yii\widgets\ActiveForm;
use app\models\Cupon;
use app\models\Report;

$model = new Report();

?>

<div class='container'>

<?php $form = ActiveForm::begin(['action' => 'index.php?r=site/report']); ?>

<div class='row'>

<div class='col-md-2'>
</div>

<div class='col-md-4'>
<?= $form->field($model, 'startDate')->widget(\yii\jui\DatePicker::classname(), [
    'language' => 'en',
    'dateFormat' => 'yyyy-MM-dd',
]) ?>
</div>

<div class='col-md-4'>
<?= $form->field($model, 'endDate')->widget(\yii\jui\DatePicker::classname(), [
    'language' => 'en',
    'dateFormat' => 'yyyy-MM-dd',
]) ?>
</div>

<div class='col-md-2'>
</div>

</div>

<br>

<div class='row'>
<center><button class='btn btn-success' type="submit">SUBMIT</button></center>
</div>

<?php ActiveForm::end(); ?>

</div>
</div
