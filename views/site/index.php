<?php

use yii\widgets\ActiveForm;
$this->title = 'Restaurant';
?>

<div class="container">
  <table class="table">
  <?php foreach ($tables as $table) { ?>
      <?php if($table->getOrdersNotBilled()->all()) { ?>
      <tr style="background-color:#FFE6E6;" >
      <?php } else { ?>
      <tr style="background-color:#E1FFE2;" >
      <?php } ?>
      <td  > <h4><?= $table->name?> <h4></td>
      <td> <h4><a href="index.php?r=kot/create&amp;tid=<?=$table->tid ?>">TAKE ORDER</a></h4></td>
      <td> <h4><a href="index.php?r=bill/create&amp;tid=<?=$table->tid ?>">BILL</a></h4></td>
      <td> <h4><a href="index.php?r=r-table/view-kots&amp;tid=<?=$table->tid ?>">VIEW KOTs</a></h4></td>
    </tr>
  <? } ?>
  </table>
</div>
