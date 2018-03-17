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

    public function printBill($orders){
        $connector = new FilePrintConnector("/dev/usb/lp0");
        $tax = Tax::find()->one();
        $printer = new Printer($connector);
        $printer -> setEmphasis(true);
        $printer-> setTextSize(2,2);
        $printer-> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text('Margao Central\'s');
        $printer -> feed(1);
        $printer -> setEmphasis(false);
        $printer-> setTextSize(1,1);
        $printer -> setFont(Printer::FONT_A);
        $printer -> text('C/o Konkan Railway Corporation Limited');
        $printer -> feed(1);
        $printer -> text('New Rawanfond Circle, Margao');
        $printer -> feed(1);
        $printer -> text('Goa - xxxxxx,');
        $printer -> feed(1);
        $printer -> text('PH: +91 xxxxxxxxxx');
        $printer -> feed(1);
        $printer -> text('GSTIN: xxxxxxxxxx');
        $printer -> feed(1);
        $printer -> text($orders[0]->table->name.'   '.date("Y-d-m H:i:s"));
        $printer -> feed(1);
        $printer -> text("BILL ID: ".$this->bid." Waiter: ".$orders[0]->kot->waiter->name);
        $printer -> feed(1);
        $printer -> setEmphasis(true);
        $printer -> setFont(Printer::FONT_A);
        $printer -> feed(1);
        $printer-> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text(' _______________________________________________');
        $printer -> feed(1);
        $printer-> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text(' Item                            Qt  Rate    Amt');
        $printer -> text(' _______________________________________________');
        $printer -> setEmphasis(false);
        for($i = 0; $i < sizeof($orders); $i++){
          $printer -> text(' ');
          $itemName = $orders[$i]->item->name;
          $quantity = $orders[$i]->quantity;
          $quantityLength = strlen($quantity);
          $cost = $orders[$i]->item->cost;
          $costLength = strlen($cost);
          $total = $quantity * $cost;
          $totalLength = strlen($total);
          $itemLength = strlen($itemName);
          $printer -> text($itemName);
          for($j = 0; $j < (31-$itemLength); $j++){
            $printer -> text(' ');
          }
          $printer -> text(' ');
          for($j = 0; $j < (2-$quantityLength); $j++){
            $printer -> text(' ');
          }
          $printer -> text($quantity);
          $printer -> text('  ');
          for($j = 0; $j < (4-$costLength); $j++){
            $printer -> text(' ');
          }
          $printer -> text($cost);
          $printer -> text(' ');
          for($j = 0; $j < (6-$totalLength); $j++){
            $printer -> text(' ');
          }
          $printer -> text($total);
          $printer -> feed(1);
        }
        $printer -> text(' _______________________________________________');
        $printer -> feed(1);
        $printer -> feed(1);
        $printer -> text('      TOTAL                          + '.$this->amount);
        $printer -> feed(1);
        $printer -> text('      CGST   @2.5                    + '.$this->tax/2);
        $printer -> feed(1);
        $printer -> text('      SGST   @2.5                    + '.$this->tax/2);
        $printer -> feed(1);
        $printer -> text('      Discount                       - '.$this->discount_amount);
        $printer -> feed(1);
        $printer -> setEmphasis(true);
        $printer-> setTextSize(1,1);
        $printer -> text('      GRAND TOTAL                   Rs '.$this->total_amount);
        $printer -> feed(1);
        $printer -> feed(1);
        $printer -> feed(1);
        $printer -> cut();
        $printer->close();

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
