<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoryvideo".
 *
 * @property int $id
 * @property int $i_user
 * @property string $name
 * @property string $color
 */
class Categoryvideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoryvideo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['i_user', 'name'], 'required'],
            [['i_user'], 'integer'],
            [['name'], 'string', 'max' => 111],
            [['color'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'i_user' => 'I User',
            'name' => 'Name',
            'color' => 'Color',
        ];
    }
}
