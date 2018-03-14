<center>
<table class="table ">
<th><h2>KOT ID<h2></th>
<th>&nbsp</th>
<th>&nbsp</th>

  <?php foreach($kots as $kot){  ?>
        <tr>
          <td>
            <h4><?= $kot->kid ?><h4>
          </td>
          <td>
            <a href="index.php?r=kot/view&amp;id=<?=$kot->kid ?>">
             <h4>RE-PRINT<h4>
            </a>
          </td>
          <td>
            <a href="index.php?r=kot/update&amp;id=<?=$kot->kid?>">
              <h4>UPDATE & PRINT<h4>
            </a>
          </td>
        </tr>

  <?php } ?>

</table>
      </center>
