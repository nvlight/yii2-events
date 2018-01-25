<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property integer $i_user
 * @property integer $i_cat
 * @property string $desc
 * @property string $dt
 * @property integer $summ
 * @property integer $type
 * @property string $note
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'i_cat']);
            //->viaTable('art_tag', ['tag_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['i_user', 'i_cat', 'desc', 'dtr'], 'required'],
            [['i_user', 'i_cat', 'summ', 'type'], 'integer'],
            [['dt'], 'safe'],
            [['dtr'], 'string', 'length' => [8]],
            [['desc'], 'string', 'max' => 101],
            [['note'], 'string', 'max' => 55],
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
            'i_cat' => 'Категория',
            'desc' => 'Описание',
            'dt' => 'Dt',
            'dtr' => 'Дата',
            'summ' => 'Summ',
            'type' => 'Type',
            'note' => 'Note',
        ];
    }
}
