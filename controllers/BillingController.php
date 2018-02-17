<?php

namespace app\controllers;

use app\components\AuthLib;
use app\components\Debug;
use app\models\User;
use Yii;

class BillingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            //return $this->goBack(['']);
            return $this->redirect([AuthLib::NOT_AUTHED_PATH]);
            //return $this->render('index', [ 'message' => $message ]);
        }
        $uid = $_SESSION['user']['id'];
        $remains = User::findOne(['id' => $uid])->remains;
        //$remains = number_format($remains, 2, ',', ' ');
        //echo Debug::d($remains,'remains');
        $this->layout = '_main';
        return $this->render('index', compact('remains'));
    }

    /*
     *
     * */
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
            // а еще может не работать потому, что это update а не save();
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
}
