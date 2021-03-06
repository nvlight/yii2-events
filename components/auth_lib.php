<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 20.10.2017
 * Time: 14:53
 */

namespace app\components;

use Yii;
use yii\web\Controller;

class AuthLib extends Controller
{
    const NOT_AUTHED_PATH = "user/login";
    const AUTHED_PATH = "billing/index";
    const LOG_OUT_PATH = "user/logout";

    //
    public static function appSessionStart(){
        //$session = new Yii::$app->session;
        if (!Yii::$app->session->isActive){
            (Yii::$app->session)->open();
        }
    }

    //
    public static function appShowSession(){
        self::appSessionStart();
        //echo Debug::d($_SESSION,'user...',1);

        return true;
    }

    //
    public static function appIsAuth()
    {
        self::appSessionStart();
        //
        if (array_key_exists('user',$_SESSION)){
            //echo 'da';
            return true;
        }
        //echo 'net';
        return false;
    }

    //
    public static function appGoAuth()
    {
        //echo 'yes';
        $a = new Controller('tmp_test','module_test');
        //echo Debug::d($a);
        die($a->redirect([AuthLib::NOT_AUTHED_PATH])->send());
    }

    //
    public static function appGoIndex()
    {
        //echo 'yes';
        $a = new Controller('tmp_test','module_test');
        //echo Debug::d($a);
        die($a->redirect([AuthLib::AUTHED_PATH])->send());
    }

    //
    public static function appLogout()
    {
        if (array_key_exists('user',$_SESSION)){
            unset($_SESSION['user']);
        }

        Yii::$app->session->close(); // закрываем сессию
        Yii::$app->session->destroy(); // уничтожаяем все связанные с ним данные

        self::appGoIndex();
    }

    //
    public static function appLogin($p)
    {

        $_SESSION['user'] = [
            'id' => $p->id,
            'uname' => $p->uname,
            'mail'  => $p->mail,
            'remains' => $p->remains
            //'upass' => $p->upass,
            //'additional' => $p->id,
        ];

        return true;
    }

    //
    public static function appGoTestLogin()
    {

        $_SESSION['user'] = ['user' => 'chich', 'pass' => 'marin','additional' => 'cancer_off'];

        return true;
    }

}