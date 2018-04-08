<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property int $id ИД
 * @property int $i_user Пользователь
 * @property int $i_cat Категория
 * @property string $title Оглавление
 * @property string $description Описание
 * @property string $url УРЛ
 * @property string $video_id ИД видео
 * @property string $note Примечание
 * @property string $duration Длительность
 * @property int $viewcount Количество просмотров
 * @property int $active Статус
 * @property string $channelid Ид канала
 * @property string $channeltitle Название канала
 * @property string $thumbnails Картинки
 * @property string $dt_publish Дата публикации
 * @property string $dt_created Дата создания
 * @property string $dt_updated Дата обновления
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['i_user', 'i_cat', 'title', 'description', 'video_id', 'duration', 'viewcount', 'channelid', 'channeltitle', 'thumbnails'], 'required'],
            [['i_user', 'i_cat', 'viewcount'], 'integer'],
            [['description', 'thumbnails'], 'string'],
            [['duration', 'dt_publish', 'dt_created', 'dt_updated'], 'safe'],
            [['title', 'video_id', 'note', 'channeltitle'], 'string', 'max' => 111],
            [['url'], 'string', 'max' => 255],
            [['active'], 'string', 'max' => 1],
            [['channelid'], 'string', 'max' => 50],
        ];
    }

    //
    public function getCategoryvideo(){
        return $this->hasOne(Categoryvideo::className(),['id' => 'i_cat']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'i_user' => 'Пользователь',
            'i_cat' => 'Категория',
            'title' => 'Оглавление',
            'description' => 'Описание',
            'url' => 'УРЛ',
            'video_id' => 'ИД видео',
            'note' => 'Примечание',
            'duration' => 'Длительность',
            'viewcount' => 'Количество просмотров',
            'active' => 'Статус',
            'channelid' => 'Ид канала',
            'channeltitle' => 'Название канала',
            'thumbnails' => 'Картинки',
            'dt_publish' => 'Дата публикации',
            'dt_created' => 'Дата создания',
            'dt_updated' => 'Дата обновления',
        ];
    }
}
