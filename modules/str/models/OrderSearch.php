<?php

namespace app\modules\str\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\str\models\Order;

/**
 * OrderSearch represents the model behind the search form of `app\modules\str\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_room', 'id_project', 'id_job','summ1','summ2',], 'integer'],
            [['volume', 'price1', 'price2', 'price3'], 'number'],
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
        $query = Order::find();

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
            'id' => $this->id,
            'id_room' => $this->id_room,
            'id_project' => $this->id_project,
            'id_job' => $this->id_job,
            'volume' => $this->volume,
            'price1' => $this->price1,
            'price2' => $this->price2,
            'price3' => $this->price3,
            'summ1' => $this->summ1,
            'summ2' => $this->summ2,
        ]);

        return $dataProvider;
    }
}
