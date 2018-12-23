<?php

namespace app\controllers;

use app\components\AuthLib;
use app\components\Debug;
use app\models\User;
use Yii;
use app\models\Billing;

class BillingController extends \yii\web\Controller
{
    /**
     *
     *
     **/
    public function actionIndex()
    {
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $uid = intval($_SESSION['user']['id']);
        if (Yii::$app->request->isGet && array_key_exists('remains',$_GET) && preg_match("#^[1-9]\d*$#",$_GET['remains']) ){
            $val = intval($_GET['remains']);
            $rs = Yii::$app->db
                ->createCommand("UPDATE `user` SET remains={$val} WHERE `id`={$_SESSION['user']['id']} ")
                ->execute();
            if ($rs) {
                $_SESSION['user']['remains'] = $val;
                Yii::$app->session->setFlash('updateRemains','Общий лимит обновлен');
            }
        }
        $remains = User::findOne(['id' => $uid])->remains;

        //$courses = Billing::getCourses();
        $courses = Billing::getCoursesCurrent();

        $this->layout = '_main';
        return $this->render('index', compact('remains', 'courses'));
    }

    // update if need the courses...
    public function actionCoursesUpdate()
    {
        Billing::getCoursesI();
    }

    //
    public function actionUpdateCourses_________1144511(){
        (new Billing())->updateCourses();
        return $this->redirect(['billing/index']);
    }

    /*
     *
     * */
    public function actionChangeUserLimit($val=0){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        if ((Yii::$app->request->isAjax)){
            $ulimit = User::findOne([$_SESSION['user']['id']]);

            if ($ulimit){
                $ulimit->remains = $val;
                $rs = $ulimit->save();
                if ($rs){
                    $_SESSION['user']['remains'] = $val;
                    $json = ['success' => 'yes', 'message' => 'Лимит успешно обновлен!','is_up' => $rs ];
                }
                $json = ['success' => 'no', 'message' => 'Во время обновления лимита произошла ошибка!',];
            }else{
                $json = ['success' => 'no', 'message' => 'Во время обновления лимита произошла ошибка!',];
            }
            die(json_encode($json));
        }
    }

    public function actionKv________112233(){

        $url = 'https://www.cbr-xml-daily.ru/daily_json.js';
        $nd = @file_get_contents($url);
        //$nd = null;

        $filename = './courses.json';
        if (!$nd) {
            $nd = file_get_contents($filename);
        }else{
            // # обновить courses.json, если у нас вариант новее, чем был

            //echo sha1($nd) . "<br>";
            //echo sha1(file_get_contents($filename)) . "<br>";
            if (sha1($nd) !== sha1(file_get_contents($filename))){
              if (@file_put_contents($filename, $nd))
                Yii::$app->session->setFlash('courses','Данные курсов валют были обновлены');
            }
        }

        $new_couser_valute = json_decode($nd,1);

        $this->layout = '_main';
        return $this->render('index',
            ['new_couser_valute' => $new_couser_valute,
                'remains' => 333
            ]
        );
    }
}
