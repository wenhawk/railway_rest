<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="container">
  <br>
  <br>
  <br>
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
          <h3>DISCOUNT</h3>
          <h2>Rs. <?= $discount ?><h2>
        </td>
        <td>
          <h3>TAX</h3>
          <h2>Rs.<?= $tax ?><h2>
        </td>
        <td>
          <h3>Amount</h3>
          <h2>Rs.<?= $amount ?><h2>
        </td>
        <td>
          <h3>TOTAL</h3>
          <h2>Rs.<?= $total_amount ?><h2>
        </td>
        <tr>
  </table>

</div>

<div class="form-group">
    <center><a href="index.php?r=bill/print&id=<?= $bill->bid?>" class="btn btn-success">PRINT</a></center>
</div>
