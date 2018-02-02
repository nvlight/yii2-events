<?php

namespace app\controllers;

use app\components\Debug;
use app\models\Category;
use app\models\ContactForm;
use app\models\Type;
use app\models\Event;
use Yii;
use yii\db\Query;

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

    public function actionEvent()
    {
        $this->layout = '_main';

        $events = Event::find()->where(['i_user' => $_SESSION['user']['id']])
            ->with('types')
            ->with('category')
            //->asArray()
            ->all();
        ;
        //echo Debug::d($events);

        return $this->render('event',compact('events'));
    }

    /*
     *
     *
     * */
    public function actionChangeEvent()
    {
        $uid = 1; $evid = 294;
        $ev = Event::find()->where(['id' => $evid, 'i_user' => $uid ])
            ->with('types')->with('category')->one();
        if (!$ev) {
            $json = ['success' => 'no', 'message' => 'Данная запись не найдена, значит обновлять то и нечего!', 'err' => ''];
            die(($json));
        }
        //echo Debug::d($ev);
        $tmp['desc'] = 'simple desc 3!';
        $tmp['summ'] = 555;
        $tmp['type'] = 4;
        $tmp['i_cat'] = 86;
        $tmp['dtr'] = '2018-04-01 14:44:44';
        $ev->desc = $tmp['desc'];
        $ev->summ = intval($tmp['summ']);
        $ev->type = intval($tmp['type']);
        $ev->i_cat = intval($tmp['i_cat']);
        $ev->dtr = $tmp['dtr'];
        $ev->dtr = Yii::$app->formatter->asTime($ev->dtr, 'yyyy-MM-dd');
        //
        if (!$ev->save()) {
            $json = ['success' => 'no', 'message' => 'При обновлении события произошла ошибка!' ];
            die(json_encode($json));
        }
        $rsu = Event::find()->where(['id' => $evid])->with('types')->with('category')->asArray()->one();
        //$rsu = $ev->toArray(); $rsu['typename'] = ''; $rsu['typecolor'] = '';
        // осталось отформатировать дату для обновленного значения!
        $rsu['dtr'] = Yii::$app->formatter->asTime($rsu['dtr'], 'dd-MM-yyyy');
        echo Debug::d($rsu,'rsu');
        //echo Debug::d($rs,'rs updated');

        $json = ['success' => 'yes', 'message' => 'Редактирование события завершено!', 'item' => $rsu];
        die(json_encode($json));
    }


    /*
     *
     *
     * */
    public function actionGetPost(){

        // get min dtr
        //s) from ;
        //$q1 = (new Query)->select(['select min(dtr'])->from('event')->all();
        $q1 = Event::find()->min('dtr');
        echo Debug::d($q1,'q1',2);

        $uid = 1; $evid = 294;
        $query = Event::find()->where(['i_user' => $uid, 'id' => $evid ])
            ->with('category')->with('types')->asArray()->one();
        //
        echo Debug::d($query,'query');
        $query['dtr'] = \Yii::$app->formatter->asTime($query['dtr'], 'dd-MM-yyyy');
        unset($query['i_user']);
        $json = ['success' => 'yes', 'message' => 'Событие получено!', 'event' => $query ];
        die(json_encode($json));

    }
}
