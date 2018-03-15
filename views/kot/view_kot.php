<?php ?>
<div class="container">
  <div class="row">
    <div class="col-md-4">
      <h2><?= $orders[0]->table->name ?></h2>
    </div>
    <div class="col-md-4">
      <h2>&nbsp</h2>
    </div>
    <div class="col-md-4">
      <h3> Waiter: <?= $waiter->name ?></h3>
    </div>
  </div>
  <table class="table ">
      <th>Item</th>
      <th>Cost</th>
      <th>Quantity</th>
      <th>Message</th>
    <form class="" action="index.php?r=kot/print&amp;kid=<?= $kot->kid ?>" method="post">
      <?php foreach($orders as $order) { ?>
        <tr>
          <td><?= $order->item->name ?></td>
          <td><?= $order->item->cost ?></td>
          <td><?= $order->quantity ?></td>
          <td><?= $order->message ?></td>
        </tr>
     <?php } ?>
  </table>
</div>
  <center><button class="btn btn-success" type="submit" name="button">PRINT</button><center>
</form>
