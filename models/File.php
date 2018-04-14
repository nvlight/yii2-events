<?php

namespace app\models;

use app\components\Debug;
use Yii;
use yii\widgets\DetailView;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property int $i_user
 * @property string $name
 * @property string $hash
 * @property string $active
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['i_user', 'name', 'hash','filesize'], 'required'],
            [['i_user','filesize'], 'integer'],
            [['name'], 'string', 'max' => 111],
            [['hash'], 'string', 'max' => 67],
            [['active'], 'string', 'max' => 1],
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
            'hash' => 'Hash',
            'active' => 'Active',
            'filesize' => 'filesize',
        ];
    }

    //
    public static function getUserFilesInfo(){

        $file_count = File::find()->where(['i_user' => $_SESSION['user']['id']])->count();
        //echo Debug::d((int)$file_count,'file_count',2);
        $file_size = File::find()->where(['i_user' => $_SESSION['user']['id']])->sum('filesize');
        //echo $file_size; die;

        return [
            'count' => (int)$file_count,
            'filesize' => (int)$file_size,
        ];
    }

}
