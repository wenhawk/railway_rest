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

    public static function mergeIdenticalOrders($orders) {
      $orderArray = [];
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
      return $orderArray;
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
