<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\AuthForm;
use Yii;
use app\models\User;
use app\components\Debug;
use app\models\UserForm;

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

    /*
     *
     * */
    public function actionIndex()
    {
        if (AuthLib::appIsAuth()){
            $this->layout = '_main';
            return $this->redirect(AuthLib::AUTHED_PATH);
        }
        return $this->redirect(AuthLib::NOT_AUTHED_PATH);
    }

    /*
     *
     * */
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

        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
//            echo Debug::d($_SESSION,'session');
//            echo Debug::d($_REQUEST,'request');

//            echo Debug::d($_SESSION['__captcha/user/captcha'],'session_user_captcha',2);
//            if (array_key_exists('AuthForm',$_REQUEST)){
//                echo Debug::d($_REQUEST['AuthForm']['verifyCode'],'request_user_captcha',2);
//            }

            //$this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
            if ($model->load(Yii::$app->request->post())
                && $model->validate()
                // его надобность отпадает т.к. валидейт вбирает его в себя
                //&& (Yii::$app->request->post('AuthForm')['verifyCode'] === $_SESSION['__captcha/user/captcha'] )
                )
            {
                //echo Debug::d($model); die;
                $parMail = $model->mail;  //$parMail = Yii::$app->request->post('AuthForm')['mail'];
                $parPass = $model->upass; //$parPass = Yii::$app->request->post('AuthForm')['upass'];
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
                    $this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
                    AuthLib::appLogin($getUser);
                    //echo Debug::d($_SESSION,'session'); die;
                    // добавим флеш сообщение и потом считаем его в биллинге
                    Yii::$app->session->setFlash('logined', 'Вы успешно авторизовались!');
                    // Yii::$app->session->getFlash('logined')
                    // Yii::$app->session->hasFlash('logined')
                    return $this->redirect([AuthLib::AUTHED_PATH]);
                }else{
                    $this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
                    $err1 = 'Неверный Емеил и/или пароль!';
                    Yii::$app->session->setFlash('logined', $err1);
                    return $this->render('login', compact('model', 'err1') );
                }
            }
            //Yii::$app->session->setFlash('logined', 'заполните поля');
            return $this->render('login', compact('model') );
        }

        $this->layout = '_main';
        return $this->redirect(AuthLib::AUTHED_PATH);
    }

    /*
     *
     *
     * */
    public function actionChangeUserInfo()
    {
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            return $this->redirect(AuthLib::NOT_AUTHED_PATH);
        }

        $this->layout = '_main';
        $uid = $_SESSION['user']['id'];
        $model = new UserForm();
        $user = User::findOne($uid);
        $model->remains = $user->remains;
        $model->uname = $user->uname;
        //echo Debug::d($_REQUEST,'request');
        //echo Debug::d($_SESSION,'$_SESSION');
        if ($model->load(Yii::$app->request->post()) && $model->validate() ){
            //echo "in weight 0 <br/>";
            $user->uname = Yii::$app->request->post('UserForm')['uname'];
            $upass = Yii::$app->request->post('UserForm')['upass'];
            $upassHashed = sha1(Yii::$app->params['my_salt'] . $upass);
            $user->remains = Yii::$app->request->post('UserForm')['remains'];

            if ($upassHashed !== $user->upass){
                //echo "in weight 1 <br/>";
                Yii::$app->session->setFlash('saved','Старый пароль не совпадает с введенным!');
                return $this->render('changeuserinfo', compact('model','user'));
            }
            $newpass1 = Yii::$app->request->post('UserForm')['newpass1'];
            $newpass2 = Yii::$app->request->post('UserForm')['newpass2'];
            if ($newpass1 !== $newpass2) {
                //echo "in weight 2 <br/>";
                Yii::$app->session->setFlash('saved','Новый пароль и его повтор не совпадают!');
                return $this->render('changeuserinfo', compact('model','user'));
            }
            $upassNewHashed = sha1(Yii::$app->params['my_salt'] . $newpass1);
            $user->upass = $upassNewHashed;
            if ($user->save()){
                //echo "in weight 3 <br/>";
                $_SESSION['user']['uname'] = $user->uname;
                $_SESSION['user']['remains'] = $user->remains;
                $user = $model;
                Yii::$app->session->setFlash('saved','Изменения сохранены!');
            }
        }
        return $this->render('changeuserinfo', compact('model'));
    }


}
