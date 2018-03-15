<?php

namespace app\models;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Yii;


class Kot extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'kot';
    }

    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['flag'], 'required'],
            [['flag'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'kid' => 'Kid',
            'timestamp' => 'Timestamp',
            'flag' => 'Flag',
        ];
    }

    public static function createKot($order,$waiter){
      $kot = new Kot();
      $kot->flag = 'true';
      $kot->timestamp = '';
      $kot->wid = $waiter->name;
      $kot->save();
      for ($i=0; $i < sizeof($order->iid); $i++) {
        $o = new Orders();
        $o->iid = $order->iid[$i];
        $o->tid = $order->tid[$i];
        $o->kid = $kot->kid;
        $o->message = $order->message[$i];
        $o->quantity = $order->quantity[$i];
        $o->rank = $order->rank[$i];
        $o->flag = 'true';
        $o->save();
      }
      return $kot;
    }

    public function isEmpty() {
      $orders = $this->getAllOrders();
      if($orders){
        return False;
      }else{
        return True;
      }
    }

    public function deleteAllOrders(){
        $orders = $this->getAllOrders();
        foreach ($orders as $order) {
          $order->flag = 'false';
          $order->save();
        }
    }

    public function getAllOrders(){
      $orders = Orders::find()->joinWith('kot')
                ->joinWith('item')
                ->where(['orders.kid' => $this->kid])
                ->andWhere(['orders.flag' => 'true'])
                ->all();
      return $orders;
    }

    public static function printKot($kid, $orders){
              if(!sizeof($orders)==0){
                $connector = new FilePrintConnector("/dev/usb/lp0");
                $printer = new Printer($connector);
                $printer -> setEmphasis(true);
                $printer-> setTextSize(2,2);
                $printer -> feed(4);
                $printer -> text('KOT ID: '.$orders[0]->kid);
                $printer -> feed(1);
                $printer -> text($orders[0]->table->name);
                $printer -> feed(1);
                $printer-> setTextSize(1,2);
                foreach($orders as $order){
                     $printer -> setEmphasis(false);
                     if($order->flag == 'true'){
                       $printer -> text($order->item->name.' x '.$order->quantity);
                     }
                     else{
                       $printer -> text($order->item->name.' x '.$order->quantity.' CANCLE');
                     }
                     $printer -> feed(1);
                }
                $printer -> cut();
                $printer->close();
              }
        }

    public function getBillKots()
    {
        return $this->hasMany(BillKot::className(), ['kid' => 'kid']);
    }

    public function getBills()
    {
        return $this->hasMany(Bill::className(), ['bid' => 'bid'])->viaTable('bill_kot', ['kid' => 'kid']);
    }

    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['kid' => 'kid']);
    }

    public function getTable()
    {
        $orders = $kot->orders;
        return $orders[0]->table->name;
    }
}
