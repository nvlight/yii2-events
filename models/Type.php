<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "type".
 *
 * @property int $id
 * @property string $name
 * @property string $color
 */
class Type extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','color','i_user'], 'required'],
            ['name', 'unique', 'targetAttribute' => ['name'],'message' =>  'Тип {name} уже занят.'],
            [['name'], 'string', 'max' => 55],
            [['name'], 'string', 'min' => 3],
            [['i_user'], 'integer'],
//            [['color'], 'string', 'max' => 6],
//            [['color'], 'string', 'min' => 3],
            [['color'], 'match', 'pattern' => '/^([0-9abcdef]+){3,6}$/i', 'message' => 'Цвет должен быть в формате abc123/cd3'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя события',
            'color' => 'Цвет события',
        ];
    }
}
