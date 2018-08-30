<?php

namespace app\modules\str\models;

use Yii;

/**
 * This is the model class for table "str_room".
 *
 * @property int $id
 * @property int $id_parent
 * @property string $name
 * @property double $height
 * @property double $perimeter
 * @property int $wall_count
 * @property double $s_roof
 * @property double $s_floor
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'str_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_parent', 'wall_count'], 'integer'],
            [['name'], 'required'],
            [['height', 'perimeter', 's_roof', 's_floor'], 'number'],
            [['name'], 'string', 'max' => 55],
        ];
    }

    //
    public function getProject(){
        return $this->hasOne(Project::className(), ['id' => 'id_parent']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_parent' => 'Id Parent',
            'name' => 'Имя',
            'height' => 'Высота',
            'perimeter' => 'Периметр',
            'wall_count' => 'Стен',
            's_roof' => 'S потолка',
            's_floor' => 'S пола',
        ];
    }
}
