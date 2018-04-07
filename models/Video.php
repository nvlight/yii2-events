<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property int $i_user
 * @property int $i_cat
 * @property string $title
 * @property string $description
 * @property string $video_id
 * @property string $note
 * @property string $duration
 * @property int $viewcount
 * @property string $channeltitle
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
            [['i_user', 'i_cat', 'title', 'description', 'video_id','url' ,'duration', 'viewcount', 'channeltitle'], 'required'],
            [['i_user', 'i_cat', 'viewcount'], 'integer'],
            [['duration', 'dt_publish', 'dt_created', 'dt_updated'], 'safe'],
            [['title', 'video_id', 'note', 'channeltitle'], 'string', 'max' => 111],
            [['description'], 'string', 'max' => 65535],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'i_user' => 'Пользователь',
            'i_cat' => 'Категория',
            'title' => 'Название',
            'description' => 'Описание',
            'video_id' => 'ИД видео',
            'url' => 'УРЛ',
            'note' => 'Примечание',
            'duration' => 'Длительность',
            'dt_publish' => 'Dt Publish',
            'dt_created' => 'Dt Created',
            'dt_updated' => 'Dt Updated',
        ];
    }
}
