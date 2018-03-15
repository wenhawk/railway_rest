<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tax".
 *
 * @property integer $tid
 * @property string $name
 * @property integer $value
 */
class Tax extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tax';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['value'], 'integer'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tid' => 'Tid',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }
}
