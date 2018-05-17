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
        $courses = Billing::getCourses();

        $this->layout = '_main';
        return $this->render('index', compact('remains', 'courses'));
    }

    //
    public function actionUpdateCourses(){
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
}
