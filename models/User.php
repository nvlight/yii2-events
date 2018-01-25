<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $mail
 * @property string $uname
 * @property string $upass
 * @property integer $i_group
 */
class User extends \yii\db\ActiveRecord
{
    //public $captcha;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mail', 'upass'], 'required'],
            [['i_group'], 'integer'],
            [['mail', 'uname', 'upass'], 'string', 'max' => 55],
            [['mail'], 'email'],
            [['remains'],'integer']
            //['captcha', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mail' => 'Email',
            'uname' => 'Имя',
            'upass' => 'Пароль',
            'i_group' => 'I Group',
            'remains' => 'Баланс'
        ];
    }
}
