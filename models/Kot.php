<?php

namespace app\models;
use app\models\Waiter;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
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

    public static function createKot($wid){
      $kot = new Kot();
      $kot->flag = 'true';
	    date_default_timezone_set('Asia/Kolkata');
      $kot->timestamp = date('Y-m-d H:i:s');
      $kot->wid = $wid;
      $kot->save();
      return $kot;
    }

    public function getAllOrders(){
      $orders = Orders::find()->joinWith('kot')
                ->joinWith('item')
                ->where(['orders.kid' => $this->kid])
                ->andWhere(['orders.flag' => 'true'])
                ->all();
      return $orders;
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

    public static function printKot($kid, $orders,$waiter,$ip){
              if(!sizeof($orders)==0){
                $connector = new NetworkPrintConnector($ip, 9100);
                $printer = new Printer($connector);
                $printer -> setEmphasis(true);
                $printer-> setTextSize(2,2);
                $printer -> feed(2);
                $printer -> text('ID: '.$orders[0]->kid.' '.$orders[0]->table->name);
                $printer -> feed(1);
                $printer -> text(''.$waiter->name);
                $printer -> feed(1);
                $printer-> setTextSize(2,1);
                $printer -> feed(1);
                foreach($orders as $order){
                     $printer -> setEmphasis(false);
                     if($order->flag == 'true'){
                       $printer -> text($order->item->name.' x '.$order->quantity.' '.$order->message);
                     }
                     else{
                       $printer -> text($order->item->name.' x '.$order->quantity.' '.$order->message.' CANCLE');
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

    public function getWaiter()
    {
        return $this->hasOne(Waiter::className(), ['wid' => 'wid']);
    }

    public function getTable()
    {
        $orders = $kot->orders;
        return $orders[0]->table->name;
    }
}
