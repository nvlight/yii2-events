<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "human".
 *
 * @property integer $id
 * @property string $name
 * @property string $fam
 * @property string $ot
 * @property integer $age
 * @property integer $column_6
 */
class Human extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'human';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'fam', 'ot', 'age'], 'required'],
            [['age'], 'integer'],
            [['name', 'fam', 'ot'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'fam' => 'Fam',
            'ot' => 'Ot',
            'age' => 'Age',
        ];
    }
}
