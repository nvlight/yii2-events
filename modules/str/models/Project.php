<?php

namespace app\modules\str\models;

use Yii;

/**
 * This is the model class for table "str_project".
 *
 * @property int $id
 * @property string $name
 * @property string $location
 * @property string $date
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'str_project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['date'], 'safe'],
            [['name', 'location'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'location' => 'Место',
            'date' => 'Дата',
        ];
    }
}
