<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\Category;
use app\models\Event;
use app\models\Type;
use app\components\Debug;
use Yii;
use yii\db\Query;

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
            return $this->redirect(AuthLib::NOT_AUTHED_PATH);
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
        if (Yii::$app->request->isAjax){
            $ev = new Event();
            if ($ev->load(Yii::$app->request->post()) ){
                //echo Debug::d($_REQUEST,'request'); die;
                $ev->i_user = $_SESSION['user']['id'];
                $ev->dtr = Yii::$app->formatter->asTime($ev->dtr, 'yyyy-MM-dd'); # 14:09
                $rs = $ev->insert();
                if ($rs) {
                    $json = ['success' => 'yes',  'message' => 'Событие успешно добавлено!'];
                }else{
                    $json = ['success' => 'no',  'message' => 'Некорректные входные данные!'];
                }
            }else{
                $json = ['success' => 'no',  'message' => 'Ошибка при добавлении категории!'];
            }
            die(json_encode($json));
        }elseif (Yii::$app->request->isPost){
            $ev = new Event();
            if ($ev->load(Yii::$app->request->post()) ) {
                //echo Debug::d($_REQUEST,'request'); die;
                //
                $ev->i_user = $_SESSION['user']['id'];
                if (!$ev->validate()) {
                    Yii::$app->session->setFlash('addPost','Некорректные входные данные!');
                    Yii::$app->session->setFlash('success','no');
                    return $this->redirect(['post/index']);
                }
                //
                $ev->dtr = Yii::$app->formatter->asTime($ev->dtr, 'yyyy-MM-dd'); # 14:09
                $rs = $ev->insert();
                //echo Debug::d($ev);
                if ($rs) {
                    Yii::$app->session->setFlash('addPost','Событие успешно добавлено!');
                    Yii::$app->session->setFlash('success','yes');
                }else{
                    Yii::$app->session->setFlash('addPost','Некорректные входные данные!');
                    Yii::$app->session->setFlash('success','no');
                }
            } else{
                Yii::$app->session->setFlash('addPost','Данные не переданы!');
                Yii::$app->session->setFlash('success','no');
            }
            return $this->redirect(['post/index']);
        }

    }

    /*
     *
     *
     **/
    public function actionAddCategory(){
        //
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            return $this->redirect(AuthLib::NOT_AUTHED_PATH);
        }
        //
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
        }elseif (Yii::$app->request->isPost){

            $model = new Category();
            $model->i_user = $_SESSION['user']['id'];
            $model->name = Yii::$app->request->post('Category')['name'];
            $model->limit = Yii::$app->request->post('Category')['limit'];
            //
            $isExistAnatherOne = Category::findOne(['name' => $model->name, 'i_user' => $_SESSION['user']['id']]);
            if ($isExistAnatherOne){
                Yii::$app->session->setFlash('addPost','Категория с таким названием уже существует!');
                Yii::$app->session->setFlash('success','no');
                return $this->redirect(['post/index']);
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
                Yii::$app->session->setFlash('addPost','Категория добавлена!');
                Yii::$app->session->setFlash('success','yes');
                return $this->redirect(['post/index']);
            }else{
                Yii::$app->session->setFlash('addPost','Ошибка при добавлении категории');
                Yii::$app->session->setFlash('success','no');
                return $this->redirect(['post/index']);
            }
        }

    }

    /*
     *
     *
     * */
    public function actionAddType(){
        //
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            return $this->redirect(AuthLib::NOT_AUTHED_PATH);
        }
        //
        if (Yii::$app->request->isAjax){
            $model = new Type();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                //Type[color] Type[name]	test
                //$tcolor = Yii::$app->request->post("Type['color']");
                //$tname =  Yii::$app->request->post("Type['name']");
                if ($model->save()){
                    $json = ['success' => 'yes', 'message' => 'Тип события добавлен!',
                        'id' => $model->id, 'name' => $model->name];
                }else{
                    $json = ['success' => 'no', 'message' => 'Ошибка при добавлении типа события'];
                }
            }else{
                $json = ['success' => 'no', 'message' => 'Валидация не удалась!'];
            }
            die(json_encode($json));
        }elseif (Yii::$app->request->isPost){
            $model = new Type();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                //echo Debug::d($_REQUEST,'request');
                //Type[color] Type[name]	test
                //$tcolor = Yii::$app->request->post("Type['color']");
                //$tname =  Yii::$app->request->post("Type['name']");
                if ($model->save()){
                    $json = ['success' => 'yes', 'message' => 'Тип события добавлен!',
                        'id' => $model->id, 'name' => $model->name];
                }else{
                    $json = ['success' => 'no', 'message' => 'Ошибка при добавлении типа события'];
                }
            }else{
                $json = ['success' => 'no', 'message' => 'Валидация не удалась!'];
            }
            Yii::$app->session->setFlash('addPost',$json['message']);
            Yii::$app->session->setFlash('success',$json['success']);
            return $this->redirect(['post/index']);
        }

    }


    /*
     *
     *
     * */
    public function actionChangeCategory(){
        //
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            return $this->redirect(AuthLib::NOT_AUTHED_PATH);
        }
        //
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
        }elseif (Yii::$app->request->isPost){

            $model = Category::findOne(['i_user' => $_SESSION['user']['id'],
                                        'id' => Yii::$app->request->post('Event')['i_cat']]);
            $model->i_user = $_SESSION['user']['id'];
            $model->name =  Yii::$app->request->post('Category')['name'];
            $model->limit = Yii::$app->request->post('Category')['limit'];
            $res = ($model->update());

            if ($res){
                $json = ['success' => 'yes', 'message' => 'Категория обновлена',
                    'id' => Yii::$app->request->post('p3'), 'name' => Yii::$app->request->post('p1')];
            }else{
                $json = ['success' => 'no',  'message' => 'Ошибка при обновлении категории'];
            }

            Yii::$app->session->setFlash('addPost',$json['message']);
            Yii::$app->session->setFlash('success',$json['success']);
            return $this->redirect(['post/index']);
        }
    }

}
