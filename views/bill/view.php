<?php
?>

<div class="container">
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
        <td>
          <h3>Discount</h3>
          <h3>Rs. <?= $discount ?><h3>
        </td>
        <td>
          <h3>Tax</h3>
          <h3>Rs.<?= $tax ?><h3>
        </td>
        <td>
          <h3>Amount</h3>
          <h3>Rs.<?= $amount ?><h3>
        </td>
        <td>
          <h3>Total</h3>
          <h3>Rs.<?= $total_amount ?><h3>
        </td>
        <tr>
  </table>

</div>

    <center><a href="index.php?r=bill/print&id=<?= $bill->bid?>" class="btn btn-success">PRINT</a></center>
