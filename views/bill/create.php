<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="container">
<h1><?= $orders[0]->table->name ?></h1>
  <table class="table ">
      <th>ITEM</th>
      <th>COST</th>
      <th>QUANTITY</th>
      <th>TOTAL</th>
      <?php foreach($orders as $order)  { ?>
        <tr>
          <td><?= $order->item->name ?></td>
          <td><?= $order->item->cost ?></td>
          <td><?= $order->quantity ?></td>
          <td><?= $order->quantity * $order->item->cost ?></td>
        </tr>
      <?php } ?>
      <tr>
        <?php $form = ActiveForm::begin(); ?>
        <td>
          <?= $form->field($bill, 'discount')->textInput(['value' => 0 ]) ?>
        </td>
        <td>
            <?= $form->field($bill, 'payment_mode')->dropDownList([ 'cash' => 'CASH', 'card' => 'Card','credit' => 'Credit' ]) ?>
        </td>
        <td>
           <?= $form->field($bill, 'print')->dropDownList([ 'yes' => 'YES', 'no' => 'NO', ]) ?>
        </td>
        <td>
          <h2><?= $amount ?><h2>
        </td>
        <tr>
  </table>

</div>

<div class="form-group">
    <center><?= Html::submitButton('SUBMIT', ['class' => 'btn btn-success']) ?></center>
</div>

<?php ActiveForm::end(); ?>
