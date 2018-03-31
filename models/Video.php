<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property int $i_user
 * @property int $i_cat
 * @property string $description
 * @property string $link
 * @property string $note
 * @property string $duration
 * @property string $dt_publish
 * @property string $dt_created
 * @property string $dt_updated
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    public function getCategoryvideo(){
        return $this->hasOne(Categoryvideo::className(),['id' => 'i_cat']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['i_user', 'i_cat', 'description', 'link'], 'required'],
            [['i_user', 'i_cat'], 'integer'],
            [['dt_publish', 'dt_created', 'dt_updated'], 'safe'],
            [['description', 'link', 'note'], 'string', 'max' => 111],
            [['duration'], 'string', 'max' => 25],
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
            'i_cat' => 'I Cat',
            'description' => 'Description',
            'link' => 'Link',
            'note' => 'Note',
            'duration' => 'Duration',
            'dt_publish' => 'Dt Publish',
            'dt_created' => 'Dt Created',
            'dt_updated' => 'Dt Updated',
        ];
    }
}
