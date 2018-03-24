<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\AuthForm;
use Yii;
use app\models\User;
use app\components\Debug;
use app\models\UserForm;
use app\models\RegistrationForm;
use yii\helpers\Html;
use app\models\RestoreForm;
use DateTime;
use yii\helpers\Url;
use yii\db\Query;

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
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        AuthLib::appGoIndex();
    }

    /*
     *
     * */
    public function actionLogout()
    {
        if (Authlib::appIsAuth()){ AuthLib::appLogout();}
        AuthLib::appGoAuth();
    }

    /*
     *
     *
     * */
    public function actionLogin(){

        //
        if (Authlib::appIsAuth()) { AuthLib::appGoIndex(); }

        $model = new AuthForm();
        $this->layout = 'for_auth';

        if ($model->load(Yii::$app->request->post())
            && $model->validate()
            )
        {
            $this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
            $mySalt = Yii::$app->params['my_salt'];
            $passWithSalt = $mySalt . $model->upass;
            $hashedPass = sha1($passWithSalt);
            $getUser = User::findOne(['mail' => $model->mail, 'upass' => $hashedPass]);
            if ($getUser){
                AuthLib::appLogin($getUser);
                Yii::$app->session->setFlash('logined', 'Вы успешно авторизовались!');
                AuthLib::appGoIndex();
            }else{
                $this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
                Yii::$app->session->setFlash('logined', 'Неверный Емеил и/или пароль!');
                return $this->render('login', compact('model', 'err1') );
            }
        }
        $this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
        //Yii::$app->session->setFlash('logined', 'заполните поля');
        return $this->render('login', compact('model') );

        //$this->layout = '_main';
        //return $this->redirect(AuthLib::AUTHED_PATH);
    }

    /*
     *
     *
     **/
    public function actionChangeUserInfo()
    {
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $this->layout = '_main';
        $uid = $_SESSION['user']['id'];
        $model = new UserForm();
        $user = User::findOne($uid);
        // следующие 2 строки, чтобы не вводить эти поля, они будет автоматически введены в форму...
        $model->remains = $user->remains;
        $model->uname = $user->uname;
        if ($model->load(Yii::$app->request->post()) && $model->validate() ){
            $user->uname = $model->uname; //Yii::$app->request->post('UserForm')['uname'];
            $upass =  $model->upass; //Yii::$app->request->post('UserForm')['upass'];
            $upassHashed = sha1(Yii::$app->params['my_salt'] . $upass);
            $user->remains = $model->remains;//Yii::$app->request->post('UserForm')['remains'];

            if ($upassHashed !== $user->upass){
                Yii::$app->session->setFlash('saved','Старый пароль не совпадает с введенным!');
                return $this->render('changeuserinfo', compact('model','user'));
            }
            $newpass1 = $model->newpass1; //Yii::$app->request->post('UserForm')['newpass1'];
            $newpass2 = $model->newpass2; //Yii::$app->request->post('UserForm')['newpass2'];
            if ($newpass1 !== $newpass2) {
                Yii::$app->session->setFlash('saved','Новый пароль и его повтор не совпадают!');
                return $this->render('changeuserinfo', compact('model','user'));
            }
            $upassNewHashed = sha1(Yii::$app->params['my_salt'] . $newpass1);
            $user->upass = $upassNewHashed;
            if ($user->save()){
                $_SESSION['user']['uname'] = $user->uname;
                $_SESSION['user']['remains'] = $user->remains;
                $user = $model;
                Yii::$app->session->setFlash('saved','Изменения сохранены!');
            }else{
                Yii::$app->session->setFlash('saved','Ошибка при сохранении данных!');
            }
        }
        return $this->render('changeuserinfo', compact('model'));
    }

    /*
     *
     *
     * */
    public function actionRegistration()
    {
        if (Authlib::appIsAuth()) { AuthLib::appGoIndex(); }

        $model = new RegistrationForm();
        $this->layout = 'for_auth';
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
            Yii::$app->session->setFlash('registrated', 'Валидация пройдена!');
            // если текущий мейл занят, то сразу выводим это!
            $getUser = User::findOne(['mail' => $model->mail]);
            if ($getUser){
                Yii::$app->session->setFlash('registrated', 'Текущий Емеил занят!');
                return $this->render('registration', compact('model') );
            }
            //
            $mySalt = Yii::$app->params['my_salt'];
            $passWithSalt = $mySalt . $model->upass;
            $hashedPass = sha1($passWithSalt);
            $user = new User();
            $user->mail = $model->mail;
            $user->uname = $model->uname;
            $user->i_group = 2;
            $user->upass = $hashedPass;
            // создаем нового пользователя и... тут же скармилваем его на вход
            if ($user->save()){
                // еще тут нужно отправить на почту письмо, что юзер зарегестрировался
                $p[1] = $user->mail; //Yii::$app->params['sw_tomail'];
                $p[21] = Yii::$app->params['sw_frommail'];
                $p[22] = Yii::$app->params['name'];
                $p[3] = 'Events - регистрация'; // subject
                $p[4] = "Вы успешно зарегистрировались в приложении Events <br>\n\n";
                $mailData = [
                    'uname' => $user->uname,
                    'umail' => $user->mail,
                    'upass' => $model->upass,
                    'udtreg' => date("d.m.Y H:i:s"),
                ];
                //
                $res = Yii::$app->mailer->compose('registration',[ 'mailData' => $mailData])
                    ->setTo($p[1])->setFrom([$p[21] => $p[22]])->setSubject($p[3])->send();
                if (!$res) {
                    $msg = 'Ошибка при отправке сообщения по почте, попробуйте повторить позже';
                    Yii::$app->session->setFlash('registrated', $msg);
                    return $this->render('login', compact('model') );
                }
                Yii::$app->session->setFlash('registrated', 'Учетная запись создана!');
                AuthLib::appLogin($user);
                return $this->redirect([AuthLib::AUTHED_PATH]);
            }
            Yii::$app->session->setFlash('registrated', 'Ошибка при регистрации');
            return $this->render('login', compact('model') );

        }
        $this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
        return $this->render('registration', compact('model') );
    }

    /*
    *
    *
    * */
    public function actionAccount()
    {
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $this->layout = '_main';
        return $this->render('account');
    }

    /*
     *
     *
     **/
    public function actionRestore()
    {
        if (Authlib::appIsAuth()) { AuthLib::appGoIndex(); }

        $model = new RestoreForm(); $isRestore = false;
        if ($model->load(Yii::$app->request->post()) && $model->validate() ){
            $s = null;
            // раз мы получили нормальную почту, нужно отправить туда урл с хешом для восстановления
            $mail = Yii::$app->request->post('RestoreForm')['email'];
            $s = User::findOne(['mail' => $mail]);
            if (!$s){
                $this->layout = 'for_auth';
                Yii::$app->session->setFlash('restore', 'Не зарегистрирован пользователь с таким емайлом!');
                return $this->render('restore',compact('model','isRestore','mail'));
            }
            $isRestore = true; $s->restore = 1;
            //print $when->format('Y-m-d H:i:s'); echo "<br>";
            $when = new DateTime(); $when->modify('+ 3 hour'); $curr_dt = new DateTime();
            $curr_dt = $curr_dt->format('Y-m-d H:i:s');
            $s->res_dt = $when->format('Y-m-d H:i:s'); $res_dt = $s->res_dt;
            $restore_hash = sha1($mail . Yii::$app->params['restore_salt'] . $curr_dt);
            $s->res_hash = $restore_hash;
            $s->update();
            if ($s) {
                $p[1] = $s->mail; $mail = $p[1];
                $p[21] = Yii::$app->params['sw_frommail'];
                $p[22] = Yii::$app->params['name'];
                $p[3] = "Events. Восстановление пароля";

                $real_link = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']
                    .'/do-restore?'.'hash='.$restore_hash;
                $real_link = Url::to(['user/do-restore','hash' => $restore_hash ], true);

                $res = Yii::$app->mailer->compose('restore',[
                    'real_link' => $real_link
                ])->setTo($p[1])->setFrom([$p[21] => $p[22]])->setSubject($p[3])->send();
            }
        }

        $this->layout = 'for_auth';
        return $this->render('restore',compact('model','isRestore','mail','res_dt'));
    }

    /*
    *
    *
    * */
    public function actionDoRestore($hash=null)
    {
        if (!AuthLib::appIsAuth()) {
            // здесь мы должны получить хеш
            //$hash = "a33f9ebb21932b71fb26614313e96b3fd22d0807";
            $err_msg = '';
            // #1 часть 2 - поле ресторе = 1 ??
            $rs = User::findOne(['res_hash' => $hash, 'restore' => 1]);
            if (!$rs){
                // отказано в сбросе, ресторе <> 1
                $err_msg = 'Сброс пароля не был запрошен и/или недействительный hash!';
                $this->layout = 'simple';
                return $this->render('dorestore',compact('rs','err_msg'));
            }
            // #2 часть - истекло ли время?
            // тут узнаем, истекло ли время - 3 часа с момента подачи заявления о сбросе
            $qq = (new Query)
                ->select("HOUR(TIMEDIFF(current_timestamp(), res_dt)) as `diff`")->from('user')
                ->where(['user.restore' => 1])
                ->andWhere(['user.res_hash' => $hash])
                ->one();
            if ( ($qq && $qq['diff'] == 0) || !$qq){
                $err_msg = 'Сброс был ободрен, однако 3 часа с момента инициация сброса пароля прошли!';
                $this->layout = 'simple';
                return $this->render('dorestore',compact('rs','err_msg'));
            }

            // сброс пароля и ресторе = 0, чтобы исключить дальнейшие сбрасывания на этом же скрипте
            $np = rand(1000,9999); $np_hash = sha1(Yii::$app->params['my_salt'] . $np);
            $rs->upass = $np_hash; $uname = $rs->mail;
            $rs->restore = 0;
            $rs->update();
            //
            $this->layout = 'simple';
            return $this->render('dorestore',compact('np','uname','err_msg'));

        }
        return AuthLib::appGoIndex();
    }
}
