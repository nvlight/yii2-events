<?php

namespace app\controllers;

use app\components\AuthLib;
use app\components\Debug;
use app\models\User;

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

}
