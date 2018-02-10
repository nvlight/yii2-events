<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
class Event extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),
            ],
        ];
    }

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

    public function getTypes() {
        return $this->hasOne(Type::className(), ['id' => 'type']);
        //->viaTable('art_tag', ['tag_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['i_user', 'i_cat', 'desc', 'dtr','type'], 'required'],
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
