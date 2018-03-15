<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "waiter".
 *
 * @property integer $wid
 * @property string $name
 *
 * @property Kot[] $kots
 */
class Waiter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'waiter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wid' => 'Wid',
            'name' => 'Waiter',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKots()
    {
        return $this->hasMany(Kot::className(), ['wid' => 'wid']);
    }
}
