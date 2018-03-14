<?php

namespace app\models;

use Yii;
use app\models\BillKot;

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
            [['payment_mode','discount'], 'required'],
            [['payment_mode','print'], 'string'],
            [['discount', 'amount'], 'integer'],
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

    public function generateBill($table, $amount) {
      $this->amount = $amount;
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

    public function getKots()
    {
        return $this->hasMany(Kot::className(), ['kid' => 'kid'])->viaTable('bill_kot', ['bid' => 'bid']);
    }
}
