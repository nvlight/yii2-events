<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\Category;
use app\models\Event;
use app\models\Type;
use app\components\Debug;
use Yii;

class PostController extends \yii\web\Controller
{
    /*
     *
     *
     * */
    public function actionIndex()
    {
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            return $this->redirect(['site/index']);
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

    /*
     *
     *
     * */
    public function actionAddEvent(){
        //
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            return $this->redirect(AuthLib::NOT_AUTHED_PATH);
        }
        //
        $ev = new Event();
        if ($ev->load(Yii::$app->request->post()) ) {
            echo Debug::d($_REQUEST,'request');
            //
            $ev->i_user = $_SESSION['user']['id'];
            if (!$ev->validate()) {
                Yii::$app->session->setFlash('addEvent','Некорректные входные данные!');
            }
            //
            $ev->dtr = Yii::$app->formatter->asTime($ev->dtr, 'yyyy-MM-dd'); # 14:09
            $rs = $ev->insert();
            //echo Debug::d($ev);
            if ($rs) {
                Yii::$app->session->setFlash('addEvent','Событие успешно добавлено!');
            }else{
                Yii::$app->session->setFlash('addEvent','Некорректные входные данные!');
            }
        } else{
            Yii::$app->session->setFlash('addEvent','Данные не переданы!');
        }
        return $this->redirect(['post/index']);
    }
}
