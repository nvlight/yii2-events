<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\Category;
use app\models\Event;
use app\models\Type;

class PostController extends \yii\web\Controller
{
    public function actionIndex()
    {
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
        $event = new Event();
        $type = new Type();
        $types = Type::find()->all();
        //echo Debug::d($types); die;

        //$cats = $cats->asArray();
        //echo Debug::d($cats,'cats');
        return $this->render('index', compact('model','cats','event','type','types') );
    }

}
