<?php

namespace app\modules\str\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\str\models\Jobtypes;

/**
 * JobtypesSearch represents the model behind the search form of `app\modules\str\models\Jobtypes`.
 */
class JobtypesSearch extends Jobtypes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'measure', 'note'], 'safe'],
            [['price1', 'price2', 'price3'], 'number'],
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
        $query = Jobtypes::find();

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
            'price1' => $this->price1,
            'price2' => $this->price2,
            'price3' => $this->price3,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'measure', $this->measure])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
