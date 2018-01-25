<?php

namespace app\models;

use Yii;
use yii\base\Model;


class UserForm extends Model
{
    //public $email;
    public $uname;
    public $upass;
    public $newpass1;
    public $newpass2;
    public $remains;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uname', 'upass','newpass1','newpass2','remains'], 'required'],
            [['uname', 'upass','newpass1','newpass2',],'string', 'max' => 55],
            [['remains'],'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uname' => 'Имя',
            'upass' => 'Старый пароль',
            'newpass1' => 'Новый пароль',
            'newpass2' => 'Повтор пароля',
            'remains' => 'Баланс'
        ];
    }
}
