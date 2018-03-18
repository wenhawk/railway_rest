<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Item;
use app\models\Waiter;

?>

<style>

.hide{
    visibility: hidden;
}

</style>

<div class='container'>

    <?php $form = ActiveForm::begin(); ?>

    <div class="item-form">

        <div class="row">

            <div class="col-md-2">
              <h2><?= $table->name ?></h2>
            </div>

            <input id="tableaj-tid" class="form-control" name="Tableaj[tid]" value='<?= $table->tid ?>' type="hidden">

            <div class="col-md-5">
            <?php echo $form->field($item, 'name')->widget(Select2::classname(), [
            'data' =>  ArrayHelper::map(Item::find()->where(['flag'=>'true'])->all(), 'iid','name'),
            'language' => 'en',
            'options' =>
            ['placeholder' => 'select',
            'onChange' => 'selectOnChange()'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
            ]);
            ?>
            </div>

            <div class="col-md-2">
            <?php echo $form->field($waiter, 'name')->widget(Select2::classname(), [
            'data' =>  ArrayHelper::map(Waiter::find()->where(['flag'=>'true'])->all(), 'wid','name'),
            'language' => 'en',
            'options' =>
            ['placeholder' => 'select',
            'onChange' => 'selectOnChange()'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
            ]);
            ?>
            </div>

            <div class="col-md-3">
              <h1><h1>
                <center><input id="addButton" type="button" class="btn btn-success" value="ADD ITEM" onclick="addField();"> <br><br></center>
            </dev>

    </div>

        <table id="myTable" class="table table-condensed">
            <th>ITEM</th>
            <th>QUANTITY</th>
            <th>MESSAGE</th>
        </table>

    <div class="form-group">
        <center><?= Html::submitButton('GENERATE KOT', ['class' => 'btn btn-success']) ?></center>
    </div>

    <?php ActiveForm::end() ?>
</div>
</div>

<script>

    rowCount = 1;

    function selectOnChange(){
      p = document.getElementById('addButton');
      p.focus();
      console.log(p);
    }

    function addField (argument) {

            var myTable = document.getElementById("myTable");
            var currentIndex = myTable.rows.length;
            var currentRow = myTable.insertRow(-1);

            var button = document.createElement("button");
            button.innerHTML = "X";
            button.className = "btn btn-danger";
            button.addEventListener("click", function() {
                p= button.parentNode.parentNode;
                p.parentNode.removeChild(p);
            });

            var item = document.getElementById("item-name");
            var itemValue = item.options[item.selectedIndex].text;

            var table = document.getElementById("tableaj-tid");
            var tableValue = table.value;

            var quantity = 1;

            var activeItem = document.createElement("input");
            activeItem.className = "form-control";
            activeItem.type = "hidden";
            activeItem.name = "Orders[iid][]";
            activeItem.id = "orders-iid";
            activeItem.value = item.value;

            var activeQuantity = document.createElement("input");
            activeQuantity.className = "form-control";
            activeQuantity.type = "number";
            activeQuantity.name = "Orders[quantity][]";
            activeQuantity.id = "orders-quantity";
            activeQuantity.value = quantity;

            var activeTable = document.createElement("input");
            activeTable.className = "form-control";
            activeTable.type = "hidden";
            activeTable.name = "Orders[tid][]";
            activeTable.id = "orders-tid";
            activeTable.value = table.value;

            var activeMessage = document.createElement("input");
            activeMessage.className = "form-control";
            activeMessage.type = "text";
            activeMessage.name = "Orders[message][]";
            activeMessage.id = "orders-message";

            var activeRank = document.createElement("input");
            activeRank.className = "form-control";
            activeRank.type = "hidden";
            activeRank.name = "Orders[rank][]";
            activeRank.id = "orders-rank";
            activeRank.value = rowCount;

            var t1 = document.createTextNode(itemValue);
            var t2 = document.createTextNode(tableValue);
            var t3 = document.createTextNode(quantity);

            var p1 = document.createElement("P");
            p1.setAttribute("name", "item" + currentIndex);
            p1.appendChild(t1);

            var p3 = document.createElement("P");
            p3.setAttribute("name", "quantity" + currentIndex);
            p3.appendChild(t3);

            var currentCell = currentRow.insertCell(-1);
            currentCell.appendChild(p1);

            currentCell = currentRow.insertCell(-1);
            currentCell.appendChild(activeQuantity);

            currentCell = currentRow.insertCell(-1);
            currentCell.className = 'hide';
            currentCell.appendChild(activeItem);

            currentCell = currentRow.insertCell(-1);
            currentCell.className = 'hide';
            currentCell.appendChild(activeTable);

            currentCell = currentRow.insertCell(-1);

            currentCell.appendChild(activeRank);
            currentCell.className = 'hide';

            currentCell = currentRow.insertCell(-1);
            currentCell.appendChild(activeMessage);

            currentCell = currentRow.insertCell(-1);
            currentCell.appendChild(button);
            rowCount++;
        }


    </script>
