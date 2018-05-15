<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\Category;
use app\models\Event;
use app\models\Type;
use app\components\Debug;
use Yii;
use yii\db\Query;
use yii\base\Security;

class PostController extends \yii\web\Controller
{

    /*
     *
     *
     * */
    public function actionIndex()
    {
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $caregory = new Category();
        $event = new Event();
        $type = new Type();

        $this->layout = '_main';
        return $this->render('index',
            compact('caregory','event','type' )
        );
    }

    /*
     *
     *
     * */
    public function actionAddEvent(){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
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
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
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
            if ($res){
                Yii::$app->session->setFlash('addPost','Категория добавлена!');
                Yii::$app->session->setFlash('success','yes');
            }else{
                Yii::$app->session->setFlash('addPost','Ошибка при добавлении категории');
                Yii::$app->session->setFlash('success','no');
            }
            return $this->redirect(['post/index']);
        }
    }

    /*
     *
     *
     * */
    public function actionAddType(){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        //

        //
        if (Yii::$app->request->isAjax){
            $model = new Type();
            //echo Debug::d(Yii::$app->request->post());
            //$model->name = Yii::$app->request->post()['Type']['name'];
            //$model->color = Yii::$app->request->post()['Type']['color'];
            $model->load(Yii::$app->request->post());
            $model->i_user = $_SESSION['user']['id'];
            if ( (1===1) && ($model->validate()) ) {
                $json = ['success' => 'no', 'message' => 'Ошибка при добавлении типа события'];
                if ($model->save()){
                    $json = [
                        'success' => 'yes', 'message' => 'Тип события добавлен!',
                        'id' => $model->id, 'name' => $model->name
                    ];
                }
            }else{
                $json = ['success' => 'no', 'message' => $model->errors];
            }
            die(json_encode($json));
        }elseif (Yii::$app->request->isPost){
            $model = new Type();
            $model->load(Yii::$app->request->post());
            $model->i_user = $_SESSION['user']['id'];
            if ($model->validate()) {

                if ($model->save()){
                    $json = ['success' => 'yes', 'message' => 'Тип события добавлен!',
                        'id' => $model->id, 'name' => $model->name];
                }else{
                    $json = ['success' => 'no', 'message' => 'Ошибка при добавлении типа события'];
                }
            }else{
                $json = ['success' => 'no', 'message' => $model->errors];
            }
            Yii::$app->session->setFlash('addPost',$json['message']);
            Yii::$app->session->setFlash('success',$json['success']);
            return $this->redirect(['post/index']);
        }
        elseif (Yii::$app->request->isGet){
//            $model = new Type();
//            //$model->name = 'some types name!';
//            //$model->color = 'eee';
//            //$model->id = 23;
//            $_POST['Type']['name'] = 'some';
//            $_POST['Type']['color'] = 'aaa';
//            echo Debug::d($_POST);
//            echo Debug::d(Yii::$app->request->post());
//            $model->load($_POST);
//            $model->i_user = 1;
//
//            if ($model->validate()) {
//
//                if ($model->save()){
//                    $json = ['success' => 'yes', 'message' => 'Тип события добавлен!',
//                        'id' => $model->id, 'name' => $model->name];
//                }else{
//                    $json = ['success' => 'no', 'message' => 'Ошибка при добавлении типа события'];
//                }
//            }else{
//                $json = ['success' => 'no', 'message' => 'Валидация не удалась!','errors' => $model->errors];
//            }
//            echo Debug::d($json);
        }
    }

    /*
     *
     *
     * */
    public function actionAddTypePjax()
    {
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        if (Yii::$app->request->isPost){
            $model = new Type();
            $model->load(Yii::$app->request->post());
            $model->i_user = $_SESSION['user']['id'];

            $isExistAnatherOne = Type::findOne(['name' => $model->name, 'i_user' => $_SESSION['user']['id']]);
            if ($isExistAnatherOne){
                Yii::$app->session->setFlash('addType','Тип событий с таким названием уже существует!');
                Yii::$app->session->setFlash('success','no');
                $categories = Category::find()->select(['name','id'])
                    ->where(['i_user' => $_SESSION['user']['id']])
                    ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
                $types = Type::find()->select(['name','id'])
                    ->where(['i_user' => $_SESSION['user']['id']])
                    ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
                return $this->render('multiple',compact('categories','types'));
            }

            if (($model->validate()) && ($model->save()) ) {
                Yii::$app->session->setFlash('addType','Тип событий добавлен!');
                Yii::$app->session->setFlash('success','yes');

            }else{
                Yii::$app->session->setFlash('addType','Тип событий не добавлен!');
                Yii::$app->session->setFlash('success','no');
            }
        }
        $categories = Category::find()->select(['name','id'])
            ->where(['i_user' => $_SESSION['user']['id']])
            ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
        $types = Type::find()->select(['name','id'])
            ->where(['i_user' => $_SESSION['user']['id']])
            ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
        return $this->render('multiple',compact('categories','types'));
    }

    /*
     *
     *
     **/
    public function actionAddCategoryPjax()
    {
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        //
        if (Yii::$app->request->isPost){

            $model = new Category();
            //$model->name = Yii::$app->request->post('Category')['name'];
            //$model->limit = Yii::$app->request->post('Category')['limit'];
            $model->load(Yii::$app->request->post());
            $model->i_user = $_SESSION['user']['id'];
            //
            $isExistAnatherOne = Category::findOne(['name' => $model->name, 'i_user' => $_SESSION['user']['id']]);
            if ($isExistAnatherOne){
                Yii::$app->session->setFlash('addCategory','Категория с таким названием уже существует!');
                Yii::$app->session->setFlash('success','no');
                $categories = Category::find()->select(['name','id'])
                    ->where(['i_user' => $_SESSION['user']['id']])
                    ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
                $types = Type::find()->select(['name','id'])
                    ->where(['i_user' => $_SESSION['user']['id']])
                    ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
                return $this->render('multiple',compact('categories','types'));
            }
            $res = ($model->insert());
            if ($res){
                Yii::$app->session->setFlash('addCategory','Категория добавлена!');
                Yii::$app->session->setFlash('success','yes');
            }else{
                Yii::$app->session->setFlash('addCategory','Ошибка при добавлении категории');
                Yii::$app->session->setFlash('success','no');
            }
            $categories = Category::find()->select(['name','id'])
                ->where(['i_user' => $_SESSION['user']['id']])
                ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
            $types = Type::find()->select(['name','id'])
                ->where(['i_user' => $_SESSION['user']['id']])
                ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
            return $this->render('multiple',compact('categories','types'));
        }
    }

    /*
     *
     *
     **/
    public function actionChangeCategoryPjax()
    {
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        //
        if (Yii::$app->request->isPost){

            $model = Category::findOne(['i_user' => $_SESSION['user']['id'], 'id' => Yii::$app->request->post('Event')['i_cat']]);
            if (!$model){
                $json = ['success' => 'no',  'message' => 'Ошибка при обновлении категории. Нет такой категории'];
            }else {
                $model->load(Yii::$app->request->post());
                $model->i_user = $_SESSION['user']['id'];
                $json = ['success' => 'no', 'message' => 'Ошибка при обновлении категории'];
                if ($model->update()) {
                    $json = ['success' => 'yes', 'message' => 'Категория обновлена'];
                }
            }
            Yii::$app->session->setFlash('changeCategory',$json['message']);
            Yii::$app->session->setFlash('success',$json['success']);
            $categories = Category::find()->select(['name','id'])
                ->where(['i_user' => $_SESSION['user']['id']])
                ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
            $types = Type::find()->select(['name','id'])
                ->where(['i_user' => $_SESSION['user']['id']])
                ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
            return $this->render('multiple',compact('categories','types'));
        }
    }

    /*
     *
     *
     * */
    public function actionAddEventPjax()
    {
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        //
        if (Yii::$app->request->isPost){
            $ev = new Event();
            $ev->load(Yii::$app->request->post());
            $ev->i_user = $_SESSION['user']['id'];
            if (!$ev->validate()) {
                Yii::$app->session->setFlash('addEvent','Некорректные входные данные!');
                Yii::$app->session->setFlash('success','no');
            }else {
                $ev->dtr = Yii::$app->formatter->asTime($ev->dtr, 'yyyy-MM-dd'); # 14:09
                $ev->summ = (int)$ev->summ;
                $rs = $ev->insert();
                if ($rs) {
                    Yii::$app->session->setFlash('addEvent', 'Событие успешно добавлено!');
                    Yii::$app->session->setFlash('success', 'yes');
                } else {
                    Yii::$app->session->setFlash('addEvent', 'При добавлении события произошла ошибка');
                    Yii::$app->session->setFlash('success', 'no');
                }
            }
            $categories = Category::find()->select(['name','id'])
                ->where(['i_user' => $_SESSION['user']['id']])
                ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
            $types = Type::find()->select(['name','id'])
                ->where(['i_user' => $_SESSION['user']['id']])
                ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
            return $this->render('multiple',compact('categories','types'));
        }
    }

    /*
     *
     *
     * */
    public function actionMultiple()
    {
        $categories = Category::find()->select(['name','id'])
            ->where(['i_user' => $_SESSION['user']['id']])
            ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
        $types = Type::find()->select(['name','id'])
            ->where(['i_user' => $_SESSION['user']['id']])
            ->indexBy('id')->orderBy(['id' => SORT_DESC])->column();
        $this->layout = '_main';
        return $this->render('multiple', [
            'types' => $types,
            'categories' => $categories,

        ]);
    }

    /*
     *
     *
     **/
    public function actionChangeCategory(){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        //
        if (Yii::$app->request->isAjax){
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

    public function actionTest()
    {
        $this->layout = '_main';
        return $this->render('test');
    }

}
