<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;

/**
 * SearchOrders represents the model behind the search form about `app\models\Orders`.
 */
class SearchOrders extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tid', 'iid', 'kid', 'quantity', 'status', 'rank'], 'integer'],
            [['flag', 'message'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Orders::find();

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
        $query->andFilterWhere([
            'tid' => $this->tid,
            'iid' => $this->iid,
            'kid' => $this->kid,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'rank' => $this->rank,
        ]);

        $query->andFilterWhere(['like', 'flag', $this->flag])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
