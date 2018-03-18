<?php

namespace app\models;

use Yii;


class Orders extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'orders';
    }


    public function rules()
    {
        return [
            [['tid', 'iid', 'kid', 'quantity', 'flag', 'rank'], 'required'],
            [['tid', 'iid', 'kid', 'quantity', 'status', 'rank'], 'integer'],
            [['flag'], 'string'],
            [['message'], 'string', 'max' => 250],
            [['tid'], 'exist', 'skipOnError' => true, 'targetClass' => RTable::className(), 'targetAttribute' => ['tid' => 'tid']],
            [['iid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['iid' => 'iid']],
            [['kid'], 'exist', 'skipOnError' => true, 'targetClass' => Kot::className(), 'targetAttribute' => ['kid' => 'kid']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'tid' => 'Tid',
            'iid' => 'Iid',
            'kid' => 'Kid',
            'quantity' => 'Quantity',
            'flag' => 'Flag',
            'status' => 'Status',
            'rank' => 'Rank',
            'message' => 'Message',
        ];
    }

    public static function ShiftOrdersToNewKot($kot,$orders){
      for($i= 0 ; $i < sizeof($orders->iid); $i++){
        $order = Orders::find()->where([
          'kid' => $orders->kid[$i],
          'rank' => $orders->rank[$i],
          'iid' => $orders->iid[$i],
        ])->one();
        if($orders->flag[$i] == 'false'){
          $newOrder = new Orders();
          $newOrder->iid = $order->iid;
          $newOrder->message = $order->message;
          $newOrder->rank = $order->rank;
          $newOrder->quantity = $order->quantity;
          $newOrder->tid = $order->tid;
          $newOrder->kid = $kot->kid;
          $newOrder->flag = 'true';
          $newOrder->status = 0;
          $newOrder->save();
          $order->flag = 'false';
          $order->save();
        }
      }
    }

    public static function mergeIdenticalOrders($orders) {
      $orderArray = [];
      if($orders){
        $newOrder = $orders[0];
        for($i=1; $i < sizeof($orders); $i++) {
          if($orders[$i]->iid == $newOrder->iid){
            $newOrder->quantity = $newOrder->quantity + $orders[$i]->quantity;
          }
          else{
            array_push($orderArray, $newOrder);
            $newOrder = $orders[$i];
          }
        }
        array_push($orderArray, $newOrder);
      }
      return $orderArray;
    }

    public static function calcualteTotal($orders){
      $amount = 0;
      foreach ($orders as $order) {
          $amount = $amount + ($order->quantity * $order->item->cost);
      }
      return $amount;
    }

    public static function getAllOrders($startDate, $endDate){
      $query = Orders::find();
      $query->joinWith('kot');
      $orders = $query->where('timestamp between \''.$startDate.'\' and \''.$endDate.'\'')
                      ->andWhere(['orders.flag'=>'true'])
                      ->orderBy(['iid'=>SORT_DESC])
                      ->all();
      $orders = Orders::mergeIdenticalOrders($orders);
      return $orders;
    }

    public static function updateOrders($formOrders, $orders) {
      $newOrderArray = [];
      for ($i=0; $i < sizeof($formOrders->iid); $i++) {
        $o = Orders::find()->where(['rank' => $orders[$i]->rank])
                          ->andWhere(['iid' => $orders[$i]->iid])
                          ->andWhere(['kid' => $orders[$i]->kid])
                          ->one();
        if($formOrders->flag[$i] == 'false'){
          $o->flag = 'false';
          $o->save();
          array_push($newOrderArray, $o);
        }
        else if($formOrders->quantity[$i] < $orders[$i]->quantity){
          $o->quantity = $formOrders->quantity[$i];
          $o->save();
          $quantity = $orders[$i]->quantity -$formOrders->quantity[$i];
          $o->quantity = $quantity;
          $o->flag = 'false';
          array_push($newOrderArray, $o);
        }
        else if($formOrders->quantity[$i] > $orders[$i]->quantity){
          $o->quantity = $formOrders->quantity[$i];
          $o->save();
          $quantity = $formOrders->quantity[$i] - $orders[$i]->quantity;
          $o->quantity = $quantity;
          $o->flag = 'true';
          array_push($newOrderArray, $o);
        }
      }
      return $newOrderArray;
    }

    public function getTable()
    {
        return $this->hasOne(RTable::className(), ['tid' => 'tid']);
    }


    public function getItem()
    {
        return $this->hasOne(Item::className(), ['iid' => 'iid']);
    }

    public function getKot()
    {
        return $this->hasOne(Kot::className(), ['kid' => 'kid']);
    }
}
