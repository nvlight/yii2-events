<?php

namespace app\controllers;

use app\components\AuthLib;
use app\components\Debug;
use app\models\authForm;
use app\models\Category;
use app\models\Event;
use app\models\Human;
use app\models\RegistrationForm;
use app\models\User;
use app\models\UserForm;
use app\models\UserSignUp;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use yii\db\Query;
use yii\base\ExitException;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use yii\swiftmailer;
use app\models\RestoreForm;
use DateTime;

class SiteController extends Controller{

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
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /*
     *
     *
     * */
    public function actionChangeUserInfo()
    {
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            return $this->redirect(['site/index']);
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
            $user->uname = Yii::$app->request->post('UserForm')['uname'];
            $upass = Yii::$app->request->post('UserForm')['upass'];
            $upassHashed = sha1(Yii::$app->params['my_salt'] . $upass);
            $user->remains = Yii::$app->request->post('UserForm')['remains'];

            if ($upassHashed !== $user->upass){
                Yii::$app->session->setFlash('saved','Старый пароль не совпадает с введенным!');
                return $this->render('changeuserinfo', compact('model','user'));
            }
            $newpass1 = Yii::$app->request->post('UserForm')['newpass1'];
            $newpass2 = Yii::$app->request->post('UserForm')['newpass2'];
            if ($newpass1 !== $newpass2) {
                Yii::$app->session->setFlash('saved','Новый пароль и его повтор не совпадают!');
                return $this->render('changeuserinfo', compact('model','user'));
            }

            if ($user->save()){
                $_SESSION['user']['uname'] = $user->uname;
                $_SESSION['user']['remains'] = $user->remains;
                $user = $model;
                Yii::$app->session->setFlash('saved','Изменения сохранены!');
            }
        }
        return $this->render('changeuserinfo', compact('model'));
    }

    /*
     *
     *
     * */
    public function actionRealLogin()
    {
        AuthLib::appGoTestLogin();
        AuthLib::appShowSession();

        //return $this->render('index', []);
    }

    /*
     *
     *
     * */
    public function actionRealLogout()
    {
        AuthLib::appSessionStart();
        AuthLib::appLogout();
        AuthLib::appShowSession();

        //return $this->goHome();
    }

    /*
     *
     *
     * */
    public function actionHello() //$message = 'Noname'
    {
        $message = Yii::$app->request->get('message');
        return $this->render('hello', compact('message'));
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }



    /*
     *
     *
     * */
    public function actionBilling(){
        //AuthLib::appSessionStart();

        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            //return $this->goBack(['']);
            return $this->redirect(['site/index']);

            //return $this->render('index', [ 'message' => $message ]);
        }
        $uid = $_SESSION['user']['id'];
        $remains = User::findOne(['id' => $uid])->remains;
        //$remains = number_format($remains, 2, ',', ' ');
        //echo Debug::d($remains,'remains');
        $this->layout = '_main';
        return $this->render('billing', compact('remains'));
    }

    /*
     *
     *
     * */
    public function actionHistory($sortcol='dtr',$sort='desc'){
        //echo Debug::d($_SESSION);
        //die;

        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            //return $this->goBack(['']);
            return $this->redirect(['site/index']);

            //return $this->render('index', [ 'message' => $message ]);
        }
        $this->layout = '_main';
        //$sortcol = 'i_cat';
        switch ($sortcol){
            case 'id'    : $sortcol = 'id'; break;
            case 'i_cat' : $sortcol = 'i_cat'; break;
            case 'desc'  : $sortcol = 'desc'; break;
            case 'summ'  : $sortcol = 'summ'; break;
            case 'dtr'   : $sortcol = 'dtr'; break;
            case 'type'  : $sortcol = 'type'; break;
            default: { echo 'vi doigralis!'; die;  }
        }
        switch ($sort){
            case 'desc': { $sort = 'asc';  $rsort2 = [$sortcol => SORT_DESC]; break; }
            default:     { $sort = 'desc'; $rsort2 = [$sortcol =>  SORT_ASC]; }
        }
        //echo $sort;
        $query = Event::find()->where(['i_user' => $_SESSION['user']['id']])->with('category')->orderBy($rsort2);
        //echo Debug::d($query,'query');
        $q_counts = 10;
        $pages = new Pagination(['totalCount' => $query->count(),'pageSize' => $q_counts, 'pageSizeParam' => false, 'forcePageParam' => false]);
        $events = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        //echo Debug::d($events,'events');
        $ev2 = Event::find()->where(['i_user' => $_SESSION['user']['id'], ])->with('category');
        return $this->render('history', compact('events','pages','sort','ev2'));
    }

    /*
     *
     *
     * */
    public function actionPlan($message=''){

        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            //return $this->goBack(['']);
            return $this->redirect(['site/index']);

            //return $this->render('index', [ 'message' => $message ]);
        }

        $subquery1 = (new \yii\db\Query)->select('summ')->from('event')->where(['=','type','1'])->andWhere(['=','category.id','event.i_cat'])->limit(1);
        //$subquery1 = (new \yii\db\Query)->select('summ')->from('event')->where(['=','type','1'])->limit(1);
        $subquery2 = (new \yii\db\Query)->select('summ')->from('event')->where(['=','type','2'])->limit(1);
        $query = (new \yii\db\Query)->select(['name', 'c1' => $subquery1,'c2' => $subquery2])->from('category');
        $res =  $query->all();
        //echo Debug::d($res);

        //$q1 = Category::find()->select(['name'])->where(['>','id',1])->with('event')->all();
        $q1 = (new Query)
            ->select(['category.name'
                //,'event.type',
                ," @p11 := (select abs(SUM(event.summ)) from event WHERE event.i_cat = category.id and event.type = 1) as 'p11'"
                ," @p12 := (select abs(SUM(event.summ)) from event WHERE event.i_cat = category.id and event.type = 2) as 'p12'"
                ," @p1 := (abs(@p11 - @p12) + 0) as 'p1' "
                , "category.`limit` as 'p2'"
                    ])
            ->from('category')->where(['i_user' => $_SESSION['user']['id']])
            ->all();
        //echo Debug::d($q1);

        // большая часть, написанная выше, сделано напросно, т.к. мы будем считать только расходы, а не их разность
        // получим здесь $remains - $all_rashod...
        $remains = $_SESSION['user']['remains'];
        $all_rashod = 0; foreach ($q1 as $qk => $qv) { $all_rashod += $qv['p12']; } //echo $qv['p12'] . "</br>"; }
        $diff_main = $remains - $all_rashod;

        // получили тут разность расходов с доходами и остаток - массив
        $catPlans = $q1;
        $this->layout = '_main';
        return $this->render('plan', compact('catPlans','remains','diff_main'));
    }

    /*
     *
     *
     **/
    public function actionLfile(){

        return \Yii::$app->response->sendFile('test/test.txt');
    }

    /*
     *
     *
     **/
    public function actionPost(){

        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            //return $this->goBack(['']);
            return $this->redirect(['site/index']);

            //return $this->render('index', [ 'message' => $message ]);
        }
        $this->layout = '_main';
        $model = new Category();
        //$cats = Category::findAll(['>=','id',0]);
        $cats = Category::find()->where(['i_user' => $_SESSION['user']['id']])->all();
        //echo Debug::d($cats);
        $event = new Event();

        //$cats = $cats->asArray();
        //echo Debug::d($cats,'cats');
        return $this->render('post', compact('model','cats','event') );

        //echo "3";

    }

    /*
     *
     *
     * */
    public function actionIndex(){

        //echo Debug::d($_SESSION); die;
        if (AuthLib::appIsAuth()){
            $this->layout = '_main';
            return $this->redirect('/web/site/billing');
        }
        return $this->redirect('/web/site/login');
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
                    // добавим флеш сообщение и потом считаем его в биллинге
                    Yii::$app->session->setFlash('logined', 'Вы успешно авторизовались!');
                    // Yii::$app->session->getFlash('logined')
                    // Yii::$app->session->hasFlash('logined')
                    return $this->redirect('/web/site/billing');
                }else{
                    $err1 = 'Неверный Емеил и/или пароль!';
                    return $this->render('login', compact('model', 'err1') );
                }
            }
            return $this->render('login', compact('model') );
        }

        $this->layout = '_main';
        return $this->redirect('/web/site/billing');
    }

    /*
     *
     *
     * */
    public function actionRegistration(){

        $model = new RegistrationForm();
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            //echo Debug::d($_REQUEST);
            //echo Debug::d($_SESSION); die;
            if ($model->load(Yii::$app->request->post())
                && (Yii::$app->request->post('RegistrationForm')['captcha'] === $_SESSION['__captcha/site/captcha'] ) )
            {
                $this->createAction('captcha')->getVerifyCode(true); // перегенерация капчи
                //echo Debug::d($_REQUEST);
                $parMail = Yii::$app->request->post('RegistrationForm')['mail'];
                $parPass = Yii::$app->request->post('RegistrationForm')['upass'];
                $parName = Yii::$app->request->post('RegistrationForm')['name'];
                $parMailEnc = Html::encode($parMail);
                $parPassEnc = Html::encode($parPass);
                $parNameEnc = Html::encode($parName);
                // если текущий мейл занят, то сразу выводим это!
                $getUser = User::findOne(['mail' => $parMail]);
                if ($getUser){
                    $err1 = 'Текущий Емеил занят!';
                    return $this->render('registration', compact('model', 'err1') );
                }
                //
                $mySalt = Yii::$app->params['my_salt'];
                $passWithSalt = $mySalt . $parPass;
                $hashedPass = sha1($passWithSalt);
                $user = new User();
                $user->mail = $parMail;
                $user->uname = $parName;
                $user->i_group = 2;
                $user->upass = $hashedPass;
//                echo Debug::d($passWithSalt,'$passWithSalt');
//                echo Debug::d($hashedPass,'$hashedPass');
//                die;
                // создаем нового пользователя и... тут же скармилваем его на вход
                if ($user->save()){
                    // еще тут нужно отправить на почту письмо, что юзер зарегестрировался
                    $p[1] = Yii::$app->params['sw_tomail'];
                    $p[21] = Yii::$app->params['sw_frommail'];
                    $p[22] = Yii::$app->params['name'];
                    $p[3] = 'Events - регистрация'; // subject
                    $p[4] = "Вы успешно зарегистрировались в приложении Events <br>\n\n";
                    //$p[4] .= 'my mail: '. $p[1] . ' | from ' . $p[21] . ' => ' .$p[22] . ' | '.date("m.d.y H:i:s");
                    $dtReg = date("m.d.y H:i:s");
                    $p[4] .= "Ваше имя: {$parNameEnc}<br>";
                    $p[4] .= "Ваша почта: {$parMailEnc}<br>";
                    $p[4] .= "Ваш пароль: {$parPassEnc}<br>";
                    $p[4] .= "Дата регистрации: {$dtReg}<br>";
                    $p[4] .= "<br/>Это сообщение отправлено автоматически, пожалуйста, не отвечайте на него<br/>";
                    $res = Yii::$app->mailer->compose()
                        ->setTo($p[1])
                        ->setFrom([$p[21] => $p[22]])
                        ->setSubject($p[3])
                        ->setTextBody($p[4])
                        ->send();
                    //echo 'status: ' . $res;
                    //$getUser = User::findOne(['mail' => $parMail, 'upass' => $hashedPass]);

                    AuthLib::appLogin($user);
                    return $this->redirect('/web/site/billing');
                }else{
                    $err1 = 'Ошибка при регистрации!';
                    return $this->render('login', compact('model', 'err1') );
                }
            }
            //die('err 1');
            return $this->render('registration', compact('model') );
        }
        //die('err 2');
        $this->layout = '_main';
        return $this->redirect('/web/site/registration');
    }


    /*
    *
    *
    * */
    public function actionRestore()
    {
        if (!AuthLib::appIsAuth()) {
            //echo Debug::d($email);
            //echo Debug::d($_REQUEST);
            $model = new RestoreForm(); $isRestore = false;
            if ($model->load(Yii::$app->request->post()) && $model->validate() ){
                //echo 'nice job';
                $s = null;
                // раз мы получили нормальную почту, нужно отправить туда урл с хешом для восстановления
                $mail = Yii::$app->request->post('RestoreForm')['email'];
                $s = User::findOne(['mail' => $mail]);
                //echo Debug::d($s,'s',2); die;
                if (!$s){
                    $this->layout = 'for_auth'; $err = 'Не зарегистрирован пользователь с таким емайлом!';
                    return $this->render('restore',compact('model','isRestore','mail','err'));
                }
                $isRestore = true;
                $s->restore = 1;
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

                    //$p[4] = "<p>hash: {$restore_hash}</p>";
                    $real_link = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']
                        .'/web/site/do-restore?'.'hash='.$restore_hash;
                    $real_link = Url::to(['site/do-restore','hash' => $restore_hash ], true);
                    $p[4] = Html::a('Восстановить доступ!', ['site/do-restore?hash='.$restore_hash], ['class' => 'btn btn-success']);
                    $text_body = <<<TB
    <h4>Приложение Events</h4>
    <h5>Сброс пароля</h5>
    <p>Для того, чтобы сбросить пароль, нужно перейти по данной ссылке и получить временный пароль</p>
    <p>
       <a href="{$real_link}"
        class="btn btn-success" target="_blank" rel="noopener" data-snippet-id="">
            {$real_link}  
       </a>
    </p>
TB;
                    $res = Yii::$app->mailer->compose('layouts/html',['content' => $text_body])
                        ->setTo($p[1])
                        ->setFrom([$p[21] => $p[22]])
                        ->setSubject($p[3])
                        ->setTextBody($text_body)
                        ->send();
                    //echo 'status: ' . $res;
                }
                //echo Debug::d($s,'my email');
            }

            $this->layout = 'for_auth';
            return $this->render('restore',compact('model','isRestore','mail','res_dt'));
        }
        return $this->redirect('/web/site/login');
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
                ->select("HOUR(TIMEDIFF(current_timestamp(), res_dt)) as `diff`")->from('user')->where(['user.restore' => 1])
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
        return $this->redirect('/web/site/login');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        //Yii::$app->user->logout();
        //return $this->goHome();
        AuthLib::appLogout();
        $this->layout = 'for_auth';
        $this->redirect('index');
    }

    /*
     *
     *
     * */
    public function actionAddCategory(){
        if (Yii::$app->request->isAjax){

            $model = new Category();
            $model->i_user = $_SESSION['user']['id'];
            $model->name = Yii::$app->request->post('Category')['name'];
            $model->limit = Yii::$app->request->post('Category')['limit'];
            //
            $isExistAnatherOne = Category::findOne(['name' => $model->name, 'i_user' => $_SESSION['user']['id']]);
            if ($isExistAnatherOne){
                $json = ['success' => 'no', 'message' => 'Категория с таким названием уже существует'];
                die(json_encode($json));
            }
            $res = ($model->insert());
            $q1 = (new Query)
                ->select("last_insert_id() as 'lid'")
                ->all();
            //echo Debug::d($q1);
            $q2 = (new Query)
                ->select("id,name")
                ->from('category')->where(['id' => $q1[0]['lid']])
                ->all();

            if ($res){
                $json = ['success' => 'yes', 'message' => 'Категория добавлена',
                            'id' => $q2[0]['id'], 'name' => $q2[0]['name']
                        ];
            }else{
                $json = ['success' => 'no',  'message' => 'Ошибка при добавлении категории'];
            }

            die(json_encode($json));
        }
    }

    /*
     *
     *
     * */
    public function actionChangeCategory(){
        if (Yii::$app->request->isAjax){
            // , 'id' =>
            $model = Category::findOne(['i_user' => $_SESSION['user']['id'], 'id' => Yii::$app->request->post('p3')]);
            $model->i_user = $_SESSION['user']['id'];
            $model->name =  Yii::$app->request->post('p1');
            $model->limit = Yii::$app->request->post('p2');
            $res = ($model->update());

            if ($res){
                $json = ['success' => 'yes', 'message' => 'Категория обновлена',
                            'id' => Yii::$app->request->post('p3'), 'name' => Yii::$app->request->post('p1')];
            }else{
                $json = ['success' => 'no',  'message' => 'Ошибка при обновлении категории'];
            }

            die(json_encode($json));
        }
    }

    /*
     *
     *
     * */
    public function actionTest(){
        $this->layout = 'simple';
        return $this->render('test');
    }

    /*
     *
     *
     * */
    public function actionTest2(){
        $when = new DateTime();
        print $when->format('Y-m-d H:i:s');
        echo "<br>";
        //$when->modify('+ 3 minute');
        print $when->format('Y-m-d H:i:s');
        echo "<br>";
        //echo date('Y-m-d H:i:s');
        //return $this->render('test2');
    }

    /*
     *
     *
     * */
    public function actionAddEvent(){
        //echo Debug::d($_SESSION,'session..');
        //echo Debug::d($_SERVER);
        if ((Yii::$app->request->method == 'POST') && AuthLib::appIsAuth()){
            //echo Debug::d($_REQUEST,'request');
            //
            $ev = new Event();
            $ev->i_user = $_SESSION['user']['id'];
            $ev->desc = Yii::$app->request->post('Event')['desc'];
            $ev->summ = intval(Yii::$app->request->post('Event')['summ']);
            $ev->type = Yii::$app->request->post('Event')['type'];
            $ev->i_cat = Yii::$app->request->post('Event')['i_cat'];
            $rs = $ev->insert();
            if ($rs) {
                echo 'event is inserted 1!';

                if(!empty($rs)){
                    //$cats = $cats->asArray();
                    //echo Debug::d($cats,'cats');
                    echo 'event is inserted 2!';
                    return $this->redirect('post');
                }
            }else{
                echo "chto-to poshlo ne tak 1!";
            }
        } else{
            echo "chto-to poshlo ne tak 2!";
        }

    }

    /*
     *
     *
     * */
    public function actionShowEvent($id=0){

        //auth
        $model = new UserSignUp();

        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                //die('chich is here');
                $chich = 'chich is here';
                return $this->render('signup', [ 'model' => $model,'chich' => $chich ]);
            }

            return $this->render('signup', [ 'model' => $model ]);
        }

        $this->layout = '_main';
        $event = Event::find()->where(['i_user' => $_SESSION['user']['id']])
            ->andWhere('id = :id', [':id' => $id])
            ->with('category')
            ->all();
        return $this->render('showevent',compact('id','event'));
    }


    /*
     *
     *
     * */
    public static function getEventRowsStrByArray($id,$desc,$summ,$dt,$cl1,$cl2,$link,$cat_name){
        $dt = Yii::$app->formatter->asTime($dt, 'dd.MM.yyyy');
        $trh = <<<TRH
<tr class="actionId_{$id}">
                                <td>{$id}</td>
<td class='item_cat'>{$cat_name}</td>
<td class='item_desc'>{$desc}</td>
<td class='item_summ'>{$summ}</td>
<td class='item_dtr'>{$dt}</td>
<td class='item_type'><span class="{$cl1}">{$cl2}</span></td>
<td>
                                    <span class="btn-action" title="Просмотр">
                                        <a class="evActionView"                                           
                                           data-id="{$id}" href="#"
                                        >
                                            <span class="glyphicon glyphicon-eye-open" ></span>
                                        </a>
                                    </span>
    <span class="btn-action" title="Редактировать">                                     
                                        <a class="evActionUpdate"                                          
                                            data-id="{$id}" href="#"
                                        >
                                            <span class="glyphicon glyphicon-pencil" >
                                            </span>
                                        </a>
                                    </span>
    <span class="btn-action" title="Удалить">
                                        <a class="evActionDelete"
                                           data-id="{$id}" href="#"
                                        >
                                            <span class="glyphicon glyphicon-trash" >
                                            </span>
                                        </a>
                                    </span>
</td>
</tr>
TRH;
        return $trh;
    }

    /*
     *
     *
     * */
    public function actionAddPostModal()
    {
        //echo Debug::d($_SESSION,'session..');
        //echo Debug::d($_SERVER);

        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {
            $ev = new Event();
            $ev->i_user = $_SESSION['user']['id'];
            $ev->desc = Yii::$app->request->post('Event')['desc'];
            $ev->summ = intval(Yii::$app->request->post('Event')['summ']);
            $ev->type = Yii::$app->request->post('Event')['type'];
            $ev->i_cat = Yii::$app->request->post('Event')['i_cat'];
            $ev->dtr = Yii::$app->request->post('Event')['dtr'];
            $ev->dtr = \Yii::$app->formatter->asTime($ev->dtr, 'yyyy-MM-dd'); # 14:09
            $rs = $ev->insert();
            if (!$rs) {
                $json = ['success' => 'no', 'message' => 'При добавлении события произошла ошибка!', 'err' => $rs];
                die(json_encode($json));
            }
            $q1 = (new Query)
                ->select("last_insert_id() as 'lid'")
                ->all();
            //echo Debug::d($q1[0]['lid']);
            //$r1 = Event::findOne(['id' => $q1[0]['lid']])->toArray();
            //$r1 = Event::find()->where(['i_user' => $_SESSION['user']['id'], 'id' => $q1[0]['lid'] ])->with('category')->all()[0];
            $ev = Event::find()->where(['i_user' => $_SESSION['user']['id'], 'id' => $q1[0]['lid']])->with('category')->one();
            //echo Debug::d($r1);

            // table row html
            switch ($ev->type){
                case 1: $evtype[1] = ['success', 'доход']; $evtypeid = 1;  break;
                case 2: $evtype[2] = ['danger',  'расход'];  $evtypeid = 2; break;
                default:$evtype[3] = ['type_undefined', 'просто событие']; $evtypeid = 0;
            }
            $llink = \yii\helpers\Url::to(['/site/show-event/?id='.$ev->id]);
            $mb_dt = mb_substr($ev->dtr,0,10);
            // new trh
            $trh = SiteController::getEventRowsStrByArray($ev->id,$ev->desc,$ev->summ,$mb_dt,$evtype[$evtypeid][0],
                         $evtype[$evtypeid][1],$llink,$ev['category']->name);
            $r1 = $ev;
            if ($r1) {
                $json = ['success' => 'yes', 'message' => 'Запись успешно добавлена!',
                    'post' => $r1,
                    'id' => $r1->id,
                    'desc' => $r1->desc,
                    'dt' => $r1->dt,
                    'summ' => $r1->summ,
                    'type' => $r1->type,
                    'category' => $r1['category']['name'],
                    'trh' => $trh
                ];
                //echo Debug::d(json_encode($json));
            } else {
                $json = ['success' => 'no', 'message' => 'При добавлении записи произошла ошибка!'];
            }
            //$json = ['success' => 'middle', 'message' => 'this is a middle type of status'];
            die(json_encode($json));
        }
    }

    /*
     *
     *
     * */
    public function actionEventDel(){

        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {

            $uid = $_SESSION['user']['id'];
            $action = 'update';
            $action = Yii::$app->request->post('action');
            $id = Yii::$app->request->post('id');
            $delById = Event::findOne(['id' => $id, 'i_user' => $uid]);
            //echo Debug::d($delById,'myDeleted rs',2);
            $res = $delById;

            switch ($action){
                case 'update': {
                    $json = ['success' => 'yes', 'message' => 'Запись успешно обновлена!'];
                    break;
                }
                default: {
                    if ($res){
                        $delById->delete();
                        $json = ['success' => 'yes', 'message' => 'Запись успешно удалена!'];
                    }else {
                        $json = ['success' => 'no', 'message' => 'Запись НЕ удалена!'];
                    }
                }
            }
            //$json = ['success' => 'middle', 'message' => 'this is a middle type of status'];
            die(json_encode($json));
        }
    }

    /*
     *
     * */
    public function actionChangePostModal(){
        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {

//            event-catid
//            event-date
//            event-desc
//            event-summ
//            event-type
//            evid

            $ev = Event::findOne(['id' => Yii::$app->request->post('evid'), 'i_user' => $_SESSION['user']['id']]);
            //$ev = Event::find(['id' => Yii::$app->request->post('evid'), 'i_user' => $_SESSION['user']['id']])->with('category')->one();

            if (!$ev){
                $json = ['success' => 'no', 'message' => 'Данная запись не найдена, значит обновлять то и нечего!', 'err' => ''];
                die(json_encode($json));
            }
            $ev->desc = Yii::$app->request->post('event-desc');
            $ev->summ = intval(Yii::$app->request->post('event-summ'));
            $ev->type = Yii::$app->request->post('event-type');
            $ev->i_cat = Yii::$app->request->post('event-catid');
            $ev->dtr = Yii::$app->request->post('event-date');
            $ev->dtr = Yii::$app->formatter->asTime($ev->dtr, 'yyyy-MM-dd');
            $cat = Category::findOne(['id' => $ev->i_cat])->name;
            $rs = $ev->update();
            $item['i_cat'] = $ev->i_cat; $item['summ'] = $ev->summ; $item['type'] = $ev->type;
            $item['dtr'] = Yii::$app->formatter->asTime($ev->dtr, 'dd-MM-yyyy');
            $item['desc'] = $ev->desc; $item['id'] = Yii::$app->request->post('evid');
            $item['cat'] = $cat;
            if (!$rs) {
                $json = ['success' => 'no', 'message' => 'При обновлении события произошла ошибка!', 'item' => $item];
                die(json_encode($json));
            }

            $json = ['success' => 'yes', 'message' => 'Редактирование события завершено!', 'item' => $item];
            die(json_encode($json));
        }
    }

    /*
     *
     * */
    public function actionGetPost($id){
        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {
            $query = Event::find()->where(['i_user' => $_SESSION['user']['id'], 'id' => $id ])->with('category')->one();
            $cat = $query['category'];
            $query = $query->toArray();
            $query['dtr'] = \Yii::$app->formatter->asTime($query['dtr'], 'dd-MM-yyyy');
            $query['cat'] = $cat->name;
            unset($query['i_user']);
            //echo Debug::d($query,'event');
            $json = ['success' => 'yes', 'message' => 'Событие получено!', 'event' => $query ];
            die(json_encode($json));
        }
    }

    /*
    *
    **/
    public function actionChangeUserLimit($val=0){
        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {
            $ulimit = User::findOne([$_SESSION['user']['id']]);
            //echo Debug::d($ulimit,'$ulimit');
            if (!$ulimit){
                $json = ['success' => 'no', 'message' => 'Во время обновления лимита произошла ошибка!',];
                die(json_encode($json));
            }
            $ulimit->remains = $val;
            $rs = $ulimit->update();
            // обновление не работает ! придется сделать как приведено ниже!
            // а не работает он потому, что в модель введена капча!
            $rs = Yii::$app->db->createCommand("UPDATE `user` SET remains={$val} WHERE `id`={$_SESSION['user']['id']} ")->execute();
            // также нужно обновить и сессионную переменную remains
            $_SESSION['user']['remains'] = $val;
            $recalc = []; // cshet and course
            $remains = $val;
            $recalc[] = round($remains/Yii::$app->params['euro'],2,PHP_ROUND_HALF_DOWN);
            $recalc[] = round($remains/Yii::$app->params['dollar'],2,PHP_ROUND_HALF_DOWN);
            $recalc[] = round(1/Yii::$app->params['euro'],4,PHP_ROUND_HALF_DOWN);
            $recalc[] = round(1/Yii::$app->params['dollar'],4,PHP_ROUND_HALF_DOWN);
            // and its a new limit for user;
            $recalc[] = $remains;
            //
            $json = ['success' => 'yes', 'message' => 'Лимит успешно обновлен!','k'=>$recalc,'is_up' => $rs ];
            die(json_encode($json));
        }
    }

    /*
    *
    **/
    public function actionSearchByColval($idCol=1,$text=''){
        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {
            // params: idCol - text
            // cat,summ,dtr,type
            $pages = '';
            switch ($idCol){
                case 2: { $colName = 'summ';
                    $rs = Event::find()->where(['i_user' => $_SESSION['user']['id'],$colName => $text ])->with('category')
                        ->limit(10)
                        //->asArray()
                        ->all()
                    ;
                    break;
                }
                case 3: { $colName = 'dtr';
                    $text = Yii::$app->formatter->asTime($text, 'yyyy-MM-dd');
                    //echo $text;
                    $rs = Event::find()->where(['i_user' => $_SESSION['user']['id'],$colName => $text ])->with('category')
                        ->limit(10)
                        //->asArray()
                        ->all()
                    ;
                    break;
                }
                case 4: { $colName = 'type';
                    $str = (string)($text);
                    $str = mb_strtolower($str); $str = trim($str);
                    $text = '';
                    if ($str === 'доход'){
                        $text = 1;
                    }elseif ($str === 'расход'){
                        $text = 2;
                    }

                    $rs = Event::find()->where(['i_user' => $_SESSION['user']['id'],$colName => $text ])->with('category')
                        ->limit(10)
                        //->asArray()
                        ->all()
                    ;
                    break;
                }
                case 5: {

                    $query = Event::find()->where(['i_user' => $_SESSION['user']['id']])->with('category');
                    //echo Debug::d($query,'query');
                    $q_counts = 10;
                    $pages = new Pagination(['totalCount' => $query->count(),'pageSize' => $q_counts,
                                    'pageSizeParam' => false, 'forcePageParam' => false, 'route' => 'site/history']);
                    $rs = $query->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
                    break;
                }
                default:{
                    $colName = 'category.name';
                    $str = (string)($text);
                    $str = mb_strtolower($str); $str = trim($str);
                    $rs = Event::find()->where(['event.i_user' => $_SESSION['user']['id'],$colName => $text ])->joinWith('category')
                        ->limit(10)
                        //->asArray()
                        ->all()
                    ;
                }
            }
            if ($rs) {  }
            //echo Debug::d($rs,'$rs');
            $pages_str = ''; if ($pages !== '') { $pages_str = LinkPager::widget([ 'pagination' => $pages ]); }
            if (!$rs){
                $json = ['success' => 'no', 'message' => 'Ошибка','rs' => [] ];
                die(json_encode($json));
            }
            //
            $nrs = [];
            // table row html
            foreach($rs as $rsk => $ev){
                switch ($ev->type){
                    case 1: $evtype[1] = ['success', 'доход']; $evtypeid = 1;  break;
                    case 2: $evtype[2] = ['danger',  'расход'];  $evtypeid = 2; break;
                    default:$evtype[3] = ['type_undefined', 'просто событие']; $evtypeid = 0;
                }
                $llink = \yii\helpers\Url::to(['/site/show-event/?id='.$ev->id]);
                $mb_dt = mb_substr($ev->dtr,0,10);
                $trh = SiteController::getEventRowsStrByArray($ev->id,$ev->desc,$ev->summ,
                    $mb_dt,$evtype[$evtypeid][0],
                    $evtype[$evtypeid][1],
                    $llink,
                    $ev['category']->name);
                $nrs[] = $trh;
            }
            $json = ['success' => 'yes', 'message' => 'Успех','rs0' => $rs, 'rs' => $nrs, 'pages' => $pages_str];
            die(json_encode($json));
        }
    }

    /*
    *
    **/
    public function actionSimpleFilter(){
        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {

            // event_type	1 2
            // event_cats	74 78
            // range1	20-12-2017
            // range2	30-12-2017

            $event_type = Yii::$app->request->get('event_type');
            $ids_type = explode(' ',$event_type);
            $event_cats = Yii::$app->request->get('event_cats');
            $ids_cats = explode(' ',$event_cats);
            $event_range1 = Yii::$app->request->get('range1');
            $event_range2 = Yii::$app->request->get('range2');
            $evr1 = \Yii::$app->formatter->asTime($event_range1, 'dd.MM.yyyy');
            $evr2 = \Yii::$app->formatter->asTime($event_range2, 'dd.MM.yyyy');
            if (!$event_range1) { $event_range1 = date('d-m-Y'); $evr1 = $event_range1; }
            if (!$event_range2) { $event_range2 = date('d-m-Y'); $evr2 = $event_range2; }
            $event_range1 = \Yii::$app->formatter->asTime($event_range1, 'yyyy-MM-dd'); # 14:09
            $event_range2 = \Yii::$app->formatter->asTime($event_range2, 'yyyy-MM-dd'); # 14:09

            //echo Debug::d($event_type,'$event_type');
            //echo Debug::d($event_cats,'$event_cats');
            //echo Debug::d($event_range1,'$event_range1');
            //echo Debug::d($event_range2,'$event_range1');

            $query = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                ->andWhere(['in', 'i_cat', $ids_cats])
                ->andWhere(['in', 'type',  $ids_type])
                ->andWhere(['>', 'summ',  0])
                ->orderBy(['id' => SORT_ASC])
                ;
                //->asArray()->all();
            //echo Debug::d($query,'in weight');
            //$query = Event::find()->where(['i_user' => $_SESSION['user']['id']])->with('category');
            $q_counts = 500; // т.к. у нас идет поиск, мы должны захватить как можно больше в 1 странице,
            // тем более переход на 2-ю и более страницы не работает хД
            $pages = new Pagination(['totalCount' => $query->count(),'pageSize' => $q_counts,
                'pageSizeParam' => false, 'forcePageParam' => false, 'route' => 'site/history']);
            $rs = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

            $fl_count = count($rs);
            if ($fl_count){
                $pages_str = '';
                if ($pages !== '') { $pages_str = LinkPager::widget([ 'pagination' => $pages ]); }
                if (!$rs){
                    $json = ['success' => 'no', 'message' => 'Ошибка','rs' => [] ];
                    die(json_encode($json));
                }
                //
                $nrs = [];
                // table row html
                foreach($rs as $rsk => $ev){
                    switch ($ev->type){
                        case 1: $evtype[1] = ['success', 'доход']; $evtypeid = 1;  break;
                        case 2: $evtype[2] = ['danger',  'расход'];  $evtypeid = 2; break;
                        default:$evtype[3] = ['type_undefined', 'просто событие']; $evtypeid = 0;
                    }
                    $llink = \yii\helpers\Url::to(['/site/show-event/?id='.$ev->id]);
                    $mb_dt = mb_substr($ev->dtr,0,10);
                    $trh = SiteController::getEventRowsStrByArray($ev->id,$ev->desc,$ev->summ,
                        $mb_dt,$evtype[$evtypeid][0],
                        $evtype[$evtypeid][1],
                        $llink,
                        $ev['category']->name);
                    $nrs[] = $trh;
                }
                // тут же мы должны получить даты начала и конца поиска, а также сумму расходов и доходов за этот период
                //
                $event_type = '1'; $ids_type = explode(' ',$event_type);
                $fl_dohody = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                    ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                    ->andWhere(['in', 'i_cat', $ids_cats])
                    ->andWhere(['in', 'type',  $ids_type])
                    ->andWhere(['>', 'summ',  0])
                    //->all()
                    ->sum('summ')
                ;
                $event_type = '2'; $ids_type = explode(' ',$event_type);
                $fl_rashody = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                    ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                    ->andWhere(['in', 'i_cat', $ids_cats])
                    ->andWhere(['in', 'type',  $ids_type])
                    ->andWhere(['>', 'summ',  0])
                    //->all()
                    ->sum('summ')
                ;
                //echo Debug::d($fl_dohody,'$fl_dohody');
                //echo Debug::d($fl_rashody,'$fl_rashody');
                $fl_dohody  = intval($fl_dohody);
                $fl_rashody = intval($fl_rashody);
                $fl_diff = abs($fl_dohody - $fl_rashody);
                if ($fl_rashody > $fl_dohody) {
                    $fl_diff *= (-1);
                }
                $trs1 = <<<TRS1
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Сумма доходов</td><td><strong>{$fl_dohody}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS1;
                $trs2 = <<<TRS2
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Сумма расходов</td><td><strong>{$fl_rashody}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS2;
                $trs3 = <<<TRS3
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Разница</td><td><strong>{$fl_diff}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS3;
                $trs = [$trs1, $trs2, $trs3]; $evr = [$evr1, $evr2];
                $json = ['success' => 'yes', 'message' => 'Фильт успешно отработал!','rs' => $nrs,
                            'pages' => $pages_str, 'trs' =>  $trs, 'evr' => $evr,
                        ];
                die(json_encode($json));
            }

            $json = ['success' => 'no', 'message' => 'Фильт успешно отработал, но ничего не нашел!', 'count' => $fl_count ];
            die(json_encode($json));
        }
    }
}
