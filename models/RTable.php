<?php

namespace app\models;

use Yii;
use app\models\Orders;

class RTable extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'r_table';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['flag'], 'string'],
            [['name'], 'string', 'max' => 250],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'tid' => 'Tid',
            'name' => 'Name',
            'flag' => 'Flag',
        ];
    }

    public function getOrdersNotBilled() {
      $orders = Orders::find()
              ->where(['not in', 'orders.kid', BillKot::find()->select('kid')])
              ->joinWith('item')
              ->andWhere(['orders.flag' => 'true'])
              ->andWhere(['orders.tid' => $this->tid ])
              ->orderBy('iid')
              ->joinWith('item')
              ->all();
      return $orders;
    }

    public function getKotNotBilled() {
      $orders = Orders::find()
              ->where(['not in', 'orders.kid', BillKot::find()->select('kid')])
              ->joinWith('item')
              ->andWhere(['orders.flag' => 'true'])
              ->andWhere(['orders.tid' => $this->tid ])
              ->orderBy('iid')
              ->joinWith('item')
              ->joinWith('kot')
              ->andWhere(['kot.flag'=>'true'])
              ->groupBy('kot.kid')
              ->all();
      $kots = [];
      foreach ($orders as $order) {
        array_push($kots, $order->kot);
      }
      return $kots;
    }

    public static function  createTable($name, $flag){
      $table = new RTable();
      $table->name = $name;
      $table->flag = $flag;
      $this->save();
    }

    public function setTableFlag($flag){
      $this->flag = $flag;
      $this->save();
    }

    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['tid' => 'tid']);
    }
}
