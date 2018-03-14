<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bill;


class SearchBill extends Bill
{

    public function rules()
    {
        return [
            [['bid', 'discount', 'amount'], 'integer'],
            [['timestamp', 'payment_mode'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        $query = Bill::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->orderBy(['bid'=>SORT_DESC]);
        $query->andFilterWhere([
            'bid' => $this->bid,
            'timestamp' => $this->timestamp,
            'discount' => $this->discount,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'payment_mode', $this->payment_mode]);

        return $dataProvider;
    }
}
