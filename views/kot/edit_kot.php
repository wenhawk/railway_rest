<?php
use yii\widgets\ActiveForm;
?>
<div class="container">
<h2><?= $orders[0]->table->name ?></h2>
  <table class="table ">
      <th>Item</th>
      <th>Quantity</th>
      <th>Status</th>
      <?php $form = ActiveForm::begin(); ?>
        <?php foreach($orders as $order) { ?>
        <tr>
          <td>
            <?= $order->item->name ?>
            <input type="hidden"  name="Orders[iid][]" value="<?= $order->iid ?>">
            <input type="hidden" name="Orders[rank][]" value="<?= $order->rank ?>">
          </td>
          <td>
            <input class="form-control" type="number" name="Orders[quantity][]" value="<?= $order->quantity ?>">
          </td>
          <td>
            <select class="form-control" name="Orders[flag][]">
              <option value="true">ACTIVE</option>
              <option value="false">DELETE</option>
            </select>
          </td>
        </tr>
     <?php } ?>
  </table>
</div>
  <center><button class="btn btn-success" type="submit" name="button">UPDATE</button><center>
<?php ActiveForm::end(); ?>
