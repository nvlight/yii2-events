<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 19.01.2018
 * Time: 20:32
 */

$p[1] = Yii::$app->params['sw_tomail'];
$p[21] = Yii::$app->params['sw_frommail'];
$p[22] = Yii::$app->params['name'];
$p[3] = 'subject simple'; // subject
$p[4] = 'my mail: '. $p[1] . ' | from ' . $p[21] . ' => ' .$p[22] . ' | '.date("m.d.y H:i:s");
$res = Yii::$app->mailer->compose()
    ->setTo($p[1])
    ->setFrom([$p[21] => $p[22]])
    ->setSubject($p[3])
    ->setTextBody($p[4])
    ->send();
echo 'status: ' . $res;