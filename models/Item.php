<?php

namespace app\models;

use Yii;

class Item extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'item';
    }

    public function rules()
    {
        return [
            [['name', 'cost'], 'required'],
            [['cost'], 'integer'],
            [['flag'], 'string'],
            [['name'], 'string', 'max' => 250],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'iid' => 'Iid',
            'name' => 'ITEM NAME',
            'cost' => 'Cost',
            'flag' => 'Flag',
        ];
    }


}
