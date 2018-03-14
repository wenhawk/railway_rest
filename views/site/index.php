<?php

use yii\widgets\ActiveForm;
$this->title = 'Restaurant';
?>

<div class="container">
  <table class="table">
    <th>Table Name</th>
    <th>&nbsp</th>
    <th>&nbsp</th>
    <th>&nbsp</th>
  <?php foreach ($tables as $table) { ?>
      <?php if($table->getOrdersNotBilled()->all()) { ?>
      <tr style="background-color:#FFE6E6;" >
      <?php } else { ?>
      <tr style="background-color:#E1FFE2;" >
      <?php } ?>
      <td  ><?= $table->name?></td>
      <td> <a href="index.php?r=kot/create&amp;tid=<?=$table->tid ?>">Generate Kot</a></td>
      <td> <a href="index.php?r=bill/create&amp;tid=<?=$table->tid ?>">Generate Bill</a></td>
      <td> <a href="index.php?r=r-table/view-kots&amp;tid=<?=$table->tid ?>">View Kots</a></td>
    </tr>
  <? } ?>
  </table>
</div>
