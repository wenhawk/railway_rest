<?php

namespace app\models;

use Yii;


class BillKot extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'bill_kot';
    }

    public function rules()
    {
        return [
            [['bid', 'kid', 'flag'], 'required'],
            [['bid', 'kid'], 'integer'],
            [['flag'], 'string'],
            [['bid'], 'exist', 'skipOnError' => true, 'targetClass' => Bill::className(), 'targetAttribute' => ['bid' => 'bid']],
            [['kid'], 'exist', 'skipOnError' => true, 'targetClass' => Kot::className(), 'targetAttribute' => ['kid' => 'kid']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bid' => 'Bid',
            'kid' => 'Kid',
            'flag' => 'Flag',
        ];
    }

    public static function createBillKots($bill,$kots){
      $billKotArray = [];
      foreach ($kots as $kot) {
        $bill_kot = new BillKot();
        $bill_kot->kid = $kot->kid;
        $bill_kot->bid = $bill->bid;
        $bill_kot->flag = 'true';
        $bill_kot->save();
        array_push($billKotArray,$bill_kot);
      }
      return $billKotArray;
    }

    public static function createBillKot($bill,$kot){
        $bill_kot = new BillKot();
        $bill_kot->kid = $kot->kid;
        $bill_kot->bid = $bill->bid;
        $bill_kot->flag = 'true';
        $bill_kot->save();
    }

    public function getBill()
    {
        return $this->hasOne(Bill::className(), ['bid' => 'bid']);
    }

    public function getKot()
    {
        return $this->hasOne(Kot::className(), ['kid' => 'kid']);
    }
}
