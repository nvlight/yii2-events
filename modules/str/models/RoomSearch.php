<?php

namespace app\modules\str\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\str\models\Room;

/**
 * RoomSearch represents the model behind the search form of `app\modules\str\models\Room`.
 */
class RoomSearch extends Room
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_parent', 'wall_count'], 'integer'],
            [['name'], 'safe'],
            [['height', 'perimeter', 's_roof', 's_floor'], 'number'],
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
        $query = Room::find();

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
            'id_parent' => $this->id_parent,
            'height' => $this->height,
            'perimeter' => $this->perimeter,
            'wall_count' => $this->wall_count,
            's_roof' => $this->s_roof,
            's_floor' => $this->s_floor,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
