<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\Event;
use yii\data\Pagination;
use Yii;

class EventController extends \yii\web\Controller
{
    /*
     *
     *
     * */
    public function actionHistory($sortcol='dtr',$sort='desc'){
        //echo Debug::d($_SESSION);
        //die;

        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            //return $this->goBack(['']);
            return $this->redirect(['site/index']);

            //return $this->render('index', [ 'message' => $message ]);
        }
        $this->layout = '_main';
        //$sortcol = 'i_cat';
        switch ($sortcol){
            case 'id'    : $sortcol = 'id'; break;
            case 'i_cat' : $sortcol = 'i_cat'; break;
            case 'desc'  : $sortcol = 'desc'; break;
            case 'summ'  : $sortcol = 'summ'; break;
            case 'dtr'   : $sortcol = 'dtr'; break;
            case 'type'  : $sortcol = 'type'; break;
            default: { echo 'vi doigralis!'; die;  }
        }
        switch ($sort){
            case 'desc': { $sort = 'asc';  $rsort2 = [$sortcol => SORT_DESC, 'id' => SORT_DESC]; break; }
            default:     { $sort = 'desc'; $rsort2 = [$sortcol =>  SORT_ASC, 'id' => SORT_DESC]; }
        }
        //echo $sort;
        $query = Event::find()->where(['i_user' => $_SESSION['user']['id']])
            ->with('category')
            ->with('types')
            ->orderBy($rsort2)
            //->asArray()
            //->all();
        ;
        //echo Debug::d($query,'query'); die;
        $q_counts = 10;
        $pages = new Pagination(['totalCount' => $query->count(),'pageSize' => $q_counts,
            'pageSizeParam' => false, 'forcePageParam' => false]);
        //echo Debug::d($pages,'pages'.$pages->offset); die;
        $events = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        //echo Debug::d($events,'events');
        $ev2 = Event::find()->where(['i_user' => $_SESSION['user']['id'], ])->with('category');
        return $this->render('history', compact('events','pages','sort','ev2'));
    }

    /*
     *
     * */
    public function actionShow($id=0){
        if (Yii::$app->request->method === 'GET'){
            $id = Yii::$app->request->get('id'); $rs = null;
            if ( preg_match("#^[1-9]\d{0,7}$#", $id)){
                $rs = Event::find()->where(['id' => $id])->with('category')->with('types')
                    ->asArray()->one();
                    //->toArray(); ->one();
            }

            $this->layout = '_main';
            return $this->render('show', compact('rs'));
        }

    }
}
