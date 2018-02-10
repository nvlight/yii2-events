<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\AuthForm;
use Yii;
use yii\captcha\Captcha;
use app\models\User;
use app\components\Debug;

class UserController extends \yii\web\Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                //'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (AuthLib::appIsAuth()){
            $this->layout = '_main';
            return $this->redirect(AuthLib::AUTHED_PATH);
        }
        return $this->redirect(AuthLib::NOT_AUTHED_PATH);
    }

    public function actionLogout()
    {
        AuthLib::appLogout();
        $this->layout = 'for_auth';
        $this->redirect([AuthLib::NOT_AUTHED_PATH]);
    }

    /*
     *
     *
     * */
    public function actionLogin(){

        $model = new AuthForm();
        // для обновления капчи при f5

        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';

            // во второй строке добавил важную проверку на капку! без этого можно было было проводить брутфорс!
            if ($model->load(Yii::$app->request->post())
                && (Yii::$app->request->post('AuthForm')['captcha'] === $_SESSION['__captcha/site/captcha'] ) )
            {
                $this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
//                echo Debug::d(Yii::$app->request->post());
//                echo Debug::d($_SESSION,'session');
//                die;
                $parMail = Yii::$app->request->post('AuthForm')['mail'];
                $parPass = Yii::$app->request->post('AuthForm')['upass'];
                //
                $mySalt = Yii::$app->params['my_salt'];
                $passWithSalt = $mySalt . $parPass;
                $hashedPass = sha1($passWithSalt);
                $getUser = User::findOne(['mail' => $parMail, 'upass' => $hashedPass]); //()->where(['mail'=>$parMail])->one();
//                echo Debug::d($parMail,'mail');
//                echo Debug::d($parPass,'upass');
//                echo Debug::d($passWithSalt,'$passWithSalt');
//                echo Debug::d($hashedPass,'$hashedPass');
//                echo Debug::d($getUser,'get User');
//                die;
                if ($getUser){
                    AuthLib::appLogin($getUser);
                    //echo Debug::d($_SESSION,'session'); die;
                    // добавим флеш сообщение и потом считаем его в биллинге
                    Yii::$app->session->setFlash('logined', 'Вы успешно авторизовались!');
                    // Yii::$app->session->getFlash('logined')
                    // Yii::$app->session->hasFlash('logined')
                    return $this->redirect([AuthLib::AUTHED_PATH]);
                }else{
                    $err1 = 'Неверный Емеил и/или пароль!';
                    return $this->render('in', compact('model', 'err1') );
                }
            }
            return $this->render('login', compact('model') );
        }

        $this->layout = '_main';
        return $this->redirect(AuthLib::AUTHED_PATH);
    }


}
