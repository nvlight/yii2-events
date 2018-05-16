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
    public $upass_repeat;
    public $mail;
    public $uname;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['mail', 'upass','uname','verifyCode'], 'required'],
            [['mail','upass','uname'], 'string', 'max' => 55],
            [['mail'], 'email'],
            ['upass_repeat', 'compare', 'compareAttribute' => 'upass'],
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
            'upass_repeat' => 'Повторите пароль',
            'mail' => 'Email',
            'uname' => 'Имя',
            'verifyCode' => 'Капча'
        ];
    }


}
