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
              ->andWhere(['orders.flag' => 'true'])
              ->andWhere(['orders.tid' => $this->tid ])
              ->orderBy('iid');
      return $orders;
    }



    public function calculateBillTotal() {
      $orders = $this->getOrdersNotBilled();
      $orders = $orders->joinWith('item')->all();
      $cost = 0;
      foreach ($orders as $order) {
        $cost = $cost + ($order->item->cost * $order->quantity);
      }
      return $cost;
    }

    public function getKotNotBilled() {
      $orders = $this->getOrdersNotBilled();
      $orders = $orders->joinWith('kot')
                ->andWhere(['kot.flag'=>'true'])
                ->groupBy('kot.kid')->all();
      $kots = [];
      foreach ($orders as $order) {
        array_push($kots, $order->kot);
      }
      return $kots;
    }

    public function createTable($name, $flag){
      $this->name = $name;
      $this->flag = $flag;
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
