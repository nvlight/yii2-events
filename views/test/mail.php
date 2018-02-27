<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 25.02.2018
 * Time: 14:30
 */

use yii\helpers\Html;

$p[1] = Yii::$app->params['test_mail'];
$p[21] = Yii::$app->params['sw_frommail'];
$p[22] = Yii::$app->params['name'];
$p[3] = 'Events - регистрация';

function setSimpleDateForMail(){

    return [
        'uname' => 'test',
        'umail' => 'test@mail.com',
        'upass' => 'password',
        'udtreg' => date("d.m.Y H:i:s"),
    ];
}
$mailData = setSimpleDateForMail();

//$res = Yii::$app->mailer->compose(
//            'registration',[
//                'mailData' => $mailData
//            ]
//    )
//
//    //$res->compose($res);
//    //->attach('@web/img/4.png')
//    ->setTo($p[1])
//    ->setFrom([$p[21] => $p[22]])
//    ->setSubject($p[3])
//    ->send();
//echo 'res: ' . $res;

?>

<style>
    h5{
        color: #4f5f6f;
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
        text-align: left;
        width: 100%;
        margin: 0;
    }
    .wrapper{
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        background-color: #677283;
        padding: 0;
        margin: 0;
    }
    .mailInner{
        padding: 15px 30px;
        color: #B1B0B7;
        min-height: 330px;
        max-width: 330px;
        border: 2px solid #ccc;
        display: block;
        background-color: #fff;
        border-radius: 0px;
        margin: 0 auto;
    }
</style>


<div class="wrapper">
    <div class="mailInner">
        <h2><i class="fa fa-sun-o" aria-hidden="true"></i> Events. Регистрация</h2>
        <h4 style="color: #2040a0;">Вы успешно зарегистрировались в приложении Events </h4>
        <h5>Ваше имя: <?=$mailData['uname']?> </h5>
        <h5>Ваша почта: <?=$mailData['umail']?></h5>
        <h5>Ваш пароль: <?=$mailData['upass']?></h5>
        <h5>Дата регистрации: <?=$mailData['dtReg']?></h5>

        <p>Это сообщение отправлено автоматически, пожалуйста, не отвечайте на него</p>
    </div>
</div>