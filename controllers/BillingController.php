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
        //echo Debug::d(Yii::$app->session);
        //echo Debug::d($_REQUEST,'request'); die;

        $uid = intval($_SESSION['user']['id']);
        if (Yii::$app->request->isGet && array_key_exists('remains',$_GET) && preg_match("#^[1-9]\d*$#",$_GET['remains']) ){
            $val = intval($_GET['remains']);
            $rs = Yii::$app->db
                ->createCommand("UPDATE `user` SET remains={$val} WHERE `id`={$_SESSION['user']['id']} ")
                ->execute();
            $_SESSION['user']['remains'] = $val;
            Yii::$app->session->setFlash('updateRemains','Общий лимит обновлен');
        }
        $remains = User::findOne(['id' => $uid])->remains;
        //$remains = number_format($remains, 2, ',', ' ');
        //echo Debug::d($remains,'remains');
        //echo Debug::d($_GET,'get');
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
            //echo Debug::d($ulimit,'$ulimit');
            if (!$ulimit){
                $json = ['success' => 'no', 'message' => 'Во время обновления лимита произошла ошибка!',];
                die(json_encode($json));
            }
            $ulimit->remains = $val;
            // чтобы это заработало, нужно чтобы новое значение было отлично от предыдущего, на это и update!
            // инчае пишеm $ulimit->save() вместо $ulimit->update() ;
            $rs = $ulimit->save();

            // обновление не работает ! придется сделать как приведено ниже!
            // а еще может не работать потому, что это update а не save();
            // а не работает он потому, что в модель введена капча!
            //$rs = Yii::$app->db
            //    ->createCommand("UPDATE `user` SET remains={$val} WHERE `id`={$_SESSION['user']['id']} ")
            //    ->execute();

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
}
