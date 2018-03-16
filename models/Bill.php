<?php

namespace app\models;

use Yii;
use app\models\BillKot;
use app\models\Tax;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class Bill extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'bill';
    }

    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['payment_mode','discount','gst','tax','discount_amount','total_amount'], 'required'],
            [['payment_mode'], 'string'],
            [['discount', 'amount','gst','tax','discount_amount','total_amount'], 'integer'],
            [['timestamp'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bid' => 'Bid',
            'timestamp' => 'Timestamp',
            'payment_mode' => 'Payment Mode',
            'discount' => 'Discount',
            'amount' => 'Amount',
        ];
    }

    public function isEmpty(){
      $billKots = BillKot::find()->where(['flag'=>'true'])->all();
      if($billKots){
        return False;
      }else{
        return True;
      }
    }

    public function generateBill($table, $amount) {
      $tax = Tax::find()->one();
      $this->gst = $tax->value;
      $this->amount = $amount;
      $this->tax = round($amount * ($tax->value/100));
      $this->total_amount = round($this->amount + $this->tax);
      $this->discount_amount = round($this->total_amount * ($this->discount/100));
      $this->save();
      $kots = $table->getKotNotBilled();
      foreach ($kots as $kot) {
        $bill_kot = new BillKot();
        $bill_kot->kid = $kot->kid;
        $bill_kot->bid = $this->bid;
        $bill_kot->flag = 'true';
        $bill_kot->save();
      }
    }

    public function getBillKots()
    {
        return $this->hasMany(BillKot::className(), ['bid' => 'bid']);
    }

    public function getTable()
    {
        $kots = $this->kots;
        $table = null;
        foreach ($kots as $kot) {
          $orders = $kot->orders;
          foreach ($orders as $order) {
            $table = $order->table;
          }
        }
        if($table){
          return $table->name;
        }else{
          return 'NONE';
        }
    }

    public function printBill($bill){
      if($bill){
        $connector = new FilePrintConnector("/dev/usb/lp0");
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

    public static function calculateBillTotal($startDate,$endDate,$column){
      $total = Bill::find()->where('timestamp between \''.$startDate.'\' and \''.$endDate.'\'')
              ->andWhere(['<>','payment_mode','credit'])
              ->sum($column);
      return $total;
    }

    public static function calculateBillTotalWithPaymentMode($startDate,$endDate,$payment_mode){
      $total = Bill::find()->where('timestamp between \''.$startDate.'\' and \''.$endDate.'\'')
              ->andWhere(['payment_mode'=>$payment_mode])
              ->sum('amount');
      return $total;
    }

    public function getKots()
    {
        return $this->hasMany(Kot::className(), ['kid' => 'kid'])->viaTable('bill_kot', ['bid' => 'bid']);
    }
}
