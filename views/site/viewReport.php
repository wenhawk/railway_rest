<div class="row">
  <center>
  <div class="col-md-3">
    <h3>TOTAL</h3>
    <h3><?= $total ?></h3>
  </div>
  <div class="col-md-3">
    <h3>CASH</h3>
    <h3><?= $cash ?></h3>
  </div>
  <div class="col-md-3">
    <h3>CARD</h3>
    <h3><?= $card ?></h3>
  </div>
  <div class="col-md-3">
    <h3>CREDIT</h3>
    <h3><?= $credit ?></h3>
  </div>
</center>
</div>
<br>
<br>
<div class="row">
<center>
  <table id='example' class="table">
    <th>Item</th>
    <th>Quantity</th>
    <th>Cost</th>
    <th>Total Cost</th>
    <?php foreach($orders as $order) {?>
      <tr>
        <td><?= $order->item->name ?></td>
        <td><?= $order->quantity ?></td>
        <td><?= $order->item->cost ?></td>
        <td><?= $order->item->cost * $order->quantity  ?></td>
      </tr>
    <?php } ?>
  </table>
</center>
</div>
