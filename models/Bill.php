<?php

namespace app\models;

use Yii;
use app\models\BillKot;
use app\models\Tax;

class Bill extends \yii\db\ActiveRecord
{
    public $print;
    public static function tableName()
    {
        return 'bill';
    }

    public function rules()
    {
        return [
            [['timestamp','print'], 'safe'],
            [['payment_mode','discount','gst','tax','discount_amount','total_amount'], 'required'],
            [['payment_mode','print'], 'string'],
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
      $this->tax = $amount * ($tax->value/100);
      $this->total_amount = $this->amount + $this->tax;
      $this->discount_amount = $this->total_amount * ($this->discount/100);
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
        $orders = $kots[0]->orders;
        return $orders[0]->table->name;
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
