<?php

/**
 * Created by PhpStorm.
 * User: lght
 * Date: 13.01.2018
 * Time: 15:06
 */

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class RegistrationForm extends Model
{
    public $upass;
    public $mail;
    public $uname;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
                [['mail', 'upass','uname'], 'required'],
                [['mail','upass','uname'], 'string', 'max' => 55],
                [['mail'], 'email'],
                ['verifyCode', 'captcha', 'captchaAction'=>'/user/captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'captcha' => 'Капча',
            'upass' => 'Пароль',
            'mail' => 'Email',
            'name' => 'Имя',
        ];
    }


}
