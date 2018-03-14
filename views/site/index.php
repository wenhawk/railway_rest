<?php

use yii\widgets\ActiveForm;
$this->title = 'Restaurant';
$count = 1;
?>

<div class="container">
  <?php foreach ($tables as $table) { ?>
    <?php if( $count == 1) {  ?>
    <div class="row">
    <?php } ?>
    <div class="col-md-3">
      <div class="card" style="width: 20rem;">
        <div class="card-block list-group list-group-flush">
          <?php if($table->getOrdersNotBilled()->all()) { ?>
          <center><h3 style="background-color:#FFE6E6;" class="card-title list-group-item"><?= $table->name?></h4></center>
          <?php } else { ?>
            <center><h3 style="background-color:#E1FFE2;" class="card-title list-group-item"><?= $table->name?></h4></center>
          <?php } ?>
        </div>
        <ul class="list-group list-group-flush">
           <li class="list-group-item">
             <center>
             <a href="index.php?r=kot/create&amp;tid=<?=$table->tid ?>">Generate Kot</a>
           </center>
           </li>
           <li class="list-group-item">
              <center>
             <a href="index.php?r=bill/create&amp;tid=<?=$table->tid ?>">Generate Bill</a>
             </center>
           </li>
           <a href="index.php?r=r-table/view-kots&amp;tid=<?=$table->tid ?>">
            <center>
              <li class="list-group-item">View Kots</a>
            </li><center>
          <li class="list-group-item">
            <select onchange="this.form.submit()" class="form-control" name="" id="">
              <option value="true">Active</option>
              <option value="false">Delete</option>
            </select>
          </li>
        </ul>
      </div>
      </div>
      <? if($count % 4 == 0 && $count != 1 ) { ?>
      </div>
      <div class="row">
      <? } ?>
      <? $count = $count + 1; ?>
  <? } ?>
</div>
