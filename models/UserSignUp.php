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
class UserSignUp extends \yii\db\ActiveRecord
{
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
            [['mail', 'upass','uname'], 'required'],
            [['i_group'], 'integer'],
            [['mail', 'uname', 'upass'], 'string', 'max' => 55,'min' => 2],
            [['mail'], 'unique'],
            [['mail'], 'email'],
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
        ];
    }
}
