<?php
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\Models\Waiter;

$waiters = Waiter::find()->where(['<>','wid',$waiter->wid])->andWhere(['flag'=>'true'])->all();

?>
<div class="container">
  <div class="row">
    <div class="col-md-4">
      <h2><?= $orders[0]->table->name ?></h2>
    </div>
    <div class="col-md-4">
      <h2>&nbsp</h2>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-4">
      <div class="form-group field-waiter-name required">
      <div class="kv-plugin-loading loading-waiter-name">&nbsp;</div><select id="waiter-name" class="form-control" name="Waiter[name]" onChange="selectOnChange()" aria-required="true" data-s2-options="s2options_d6851687" data-krajee-select2="select2_c9724452" >
      <option value="<?= $waiter->wid ?>"><?= $waiter->name ?></option>
      <?php foreach($waiters as $waiter){ ?>
      <option value="<?= $waiter->wid ?>"><?= $waiter->name ?></option>
      <? } ?>
      </select>

      <div class="help-block"></div>
      </div>
    </div>
  </div>
  <table class="table ">
      <th>Item</th>
      <th>Quantity</th>
      <th>Status</th>

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
            <?= $order->message ?>
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
