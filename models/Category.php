<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $i_user
 * @property string $name
 * @property integer $limit
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    public function getEvent(){
        return $this->hasOne(Event::className(), ['i_cat' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['i_user', 'name','limit'], 'required'],
            [['i_user', 'limit'], 'integer'],
            [['name'], 'string', 'max' => 55],
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
            'name' => 'категория',
            'limit' => 'лимит',
        ];
    }
}
