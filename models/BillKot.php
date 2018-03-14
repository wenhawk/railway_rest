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

    public function getBill()
    {
        return $this->hasOne(Bill::className(), ['bid' => 'bid']);
    }

    public function getKot()
    {
        return $this->hasOne(Kot::className(), ['kid' => 'kid']);
    }
}
