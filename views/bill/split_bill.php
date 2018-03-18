<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>
<div class="container">
  <div class="row">
    <div class="col-md-4">
      <h2><?= $table->name ?></h2>
    </div>
  </div>
    <?php $form = ActiveForm::begin(); ?>
  <table class="table ">
      <th>Item</th>
      <th>Quantity</th>
      <th>Message</th>

        <?php foreach($orders as $order) { ?>
        <tr>
          <td>
            <?= $order->item->name ?>
            <input type="hidden"  name="Orders[iid][]" value="<?= $order->iid ?>">
            <input type="hidden" name="Orders[rank][]" value="<?= $order->rank ?>">
            <input type="hidden" name="Orders[kid][]" value="<?= $order->kid ?>">
          </td>
          <td>
          <?= $order->quantity ?>
          </td>
          <td>
            <?= $order->message ?>
          </td>
          <td>
            <select class="form-control" name="Orders[flag][]">
              <option value="true">DONT SPLIT</option>
              <option value="false">SPLIT</option>
            </select>
          </td>
        </tr>
     <?php } ?>
  </table>
</div>
  <center><button class="btn btn-success" type="submit" name="button">SPLIT</button><center>
<?php ActiveForm::end(); ?>
