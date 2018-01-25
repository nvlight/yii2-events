<?php

namespace app\controllers;

use app\components\Debug;
use app\models\Category;
use app\models\ContactForm;
use Yii;

class TestController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = '_main';
        $model = new ContactForm();
        //session_start();
        //$this->layout = '_main';
        //$cats = Category::find()->where(['i_user' => $_SESSION['user']['id']])->all();
        //echo Debug::d($cats,'cats');
        //echo Debug::d(Yii::$app->db);

        return $this->render('index',['model' => $model]);
    }

}
