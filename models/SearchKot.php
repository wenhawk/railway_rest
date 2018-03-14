<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Kot;

/**
 * SearchKot represents the model behind the search form about `app\models\Kot`.
 */
class SearchKot extends Kot
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kid'], 'integer'],
            [['timestamp', 'flag'], 'safe'],
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
        $query = Kot::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 10 ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->orderBy(['kid'=>SORT_DESC]);
        $query->andFilterWhere([
            'kid' => $this->kid,
            'timestamp' => $this->timestamp,
        ]);

        $query->andFilterWhere(['like', 'flag', $this->flag]);

        return $dataProvider;
    }
}
