<?php

namespace app\controllers;

use app\components\AuthLib;
use app\components\Debug;
use app\models\authForm;
use app\models\Category;
use app\models\Event;
use app\models\RegistrationForm;
use app\models\Type;
use app\models\User;
use app\models\UserSignUp;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use app\models\ContactForm;
use yii\db\Query;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use app\models\RestoreForm;
use DateTime;

class SiteController extends Controller{

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
        // nice joke )
        return \Yii::$app->response->sendFile('test/test.txt');
    }

    /*
     *
     *
     **/
    public function actionTmail(){
        // nice joke )
        $p[1] = 'iduso@mail.ru';
        $p[21] = Yii::$app->params['sw_frommail'];
        $p[22] = Yii::$app->params['name'];
        $p[3] = 'Events - регистрация'; // subject
        $p[4] = "Вы успешно зарегистрировались в приложении Events <br>\n\n";
        $dtReg = date("m.d.y H:i:s");
        $p[4] .= "Ваше имя: name<br>";
        $p[4] .= "Ваша почта: mail<br>";
        $p[4] .= "Ваш пароль: pass<br>";
        $p[4] .= "Дата регистрации: dt_reg<br>";
        $p[4] .= "<br/>Это сообщение отправлено автоматически, пожалуйста, не отвечайте на него<br/>";
        $res = Yii::$app->mailer->compose()
            ->setTo($p[1])
            ->setFrom([$p[21] => $p[22]])
            ->setSubject($p[3])
            ->setTextBody($p[4])
            ->send();
        echo 'done';
    }

    /*
     *
     *
     **/
    public function actionTmail2(){
        $p[1] = 'iduso@mail.ru';
        $p[21] = Yii::$app->params['sw_frommail'];
        $p[22] = Yii::$app->params['name'];
        $p[3] = "Events. Восстановление пароля";
        $p[4] = Html::a('Восстановить доступ!', ['user/do-restore?hash='.'reshash'], ['class' => 'btn btn-success']);
        $text_body = <<<TB
    <h4>Приложение Events</h4>
    <h5>Сброс пароля</h5>
    <p>Для того, чтобы сбросить пароль, нужно перейти по данной ссылке и получить временный пароль</p>
    <p>
       <a href="{11}"
        class="btn btn-success" target="_blank" rel="noopener" data-snippet-id="">
            {22}  
       </a>
    </p>
TB;
        $res = Yii::$app->mailer->compose('layouts/html',['content' => $text_body])
            ->setTo($p[1])
            ->setFrom([$p[21] => $p[22]])
            ->setSubject($p[3])
            ->setTextBody($text_body)
            ->send();
        echo 'res: ' . $res;
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
                    $rs = Event::find()->where(['i_user' => $_SESSION['user']['id'],$colName => $text ])->with('category')->with('types')
                        ->limit(10)
                        //->asArray()
                        ->all()
                    ;
                    break;
                }
                case 3: { $colName = 'dtr';
                    $text = Yii::$app->formatter->asTime($text, 'yyyy-MM-dd');
                    //echo $text;
                    $rs = Event::find()->where(['i_user' => $_SESSION['user']['id'],$colName => $text ])->with('category')->with('types')
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

                    $rs = Event::find()->where(['i_user' => $_SESSION['user']['id'],$colName => $text ])->with('category')->with('types')
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
                $llink = \yii\helpers\Url::to(['/site/show-event/?id='.$ev->id]);
                $mb_dt = mb_substr($ev->dtr,0,10);
                $trh = SiteController::getEventRowsStrByArray($ev->id,$ev->desc,$ev->summ,
                    $mb_dt,
                    $ev->types['name'],
                    $ev->types['color'],
                    $llink,
                    $ev['category']->name);
                $nrs[] = $trh;
            }
            $json = ['success' => 'yes', 'message' => 'Успех','rs0' => $rs, 'rs' => $nrs, 'pages' => $pages_str];
            die(json_encode($json));
        }
    }



}
