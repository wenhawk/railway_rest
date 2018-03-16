
  <?php foreach($kots as $kot){  ?>

    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <h3> KOT ID : <?= $kot->kid ?></h3>
        </div>
        <div class="col-md-3">
          <h3> Waiter: <?= $kot->waiter->name ?></h3>
        </div>
        <div class="col-md-3">
          <a class='btn btn-success' href="index.php?r=kot/view&amp;id=<?=$kot->kid ?>">
           RE-PRINT
          </a>
        </div>
        <div class="col-md-3">
          <a class='btn btn-success' href="index.php?r=kot/update&amp;id=<?=$kot->kid?>">
            UPDATE & PRINT
          </a>
        </div>
      </div>
      <table class="table ">
          <th>Item</th>
          <th>Cost</th>
          <th>Quantity</th>
          <th>Message</th>
          <?php
          $orders = $kot->getAllOrders();
          foreach($orders as $order) { ?>
            <tr>
              <td><?= $order->item->name ?></td>
              <td><?= $order->item->cost ?></td>
              <td><?= $order->quantity ?></td>
              <td><?= $order->message ?></td>
            </tr>
         <?php } ?>
      </table>
    </div>


  <?php } ?>
