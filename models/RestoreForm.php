<?php

namespace app\models;

use yii\base\Model;

class RestoreForm extends Model
{
    public $email;
    public $verifyCode;

    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha', 'captchaAction'=>'/user/captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Емейл',
        ];
    }
}