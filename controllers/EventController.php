<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\Event;
use yii\data\Pagination;
use Yii;
use yii\db\Query;
use app\models\Type;
use yii\widgets\LinkPager;
use app\components\Debug;

class EventController extends \yii\web\Controller
{
    /*
     *
     *
     * */
    public function actionHistory($sortcol='dtr',$sort='desc'){
        if (!AuthLib::appIsAuth()){
            $this->layout = 'for_auth';
            return $this->redirect(['site/index']);
        }
        $this->layout = '_main';
        $getEvents = Event::getHistory($sortcol,$sort);
        //echo Debug::d($getEvents,'getEvents',1); die;
        return $this->render('history',
            ['events' => $getEvents[0],'pages' => $getEvents[1],'sort' => $getEvents[2],'ev2' => $getEvents[3] ]
        );
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

    /*
     *
     * */
    public function actionGet($id){
        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {
            $query = Event::find()->where(['i_user' => $_SESSION['user']['id'], 'id' => $id ])
                ->with('category')->with('types')->asArray()->one();
            //echo Debug::d($query,'query');
            $query['dtr'] = \Yii::$app->formatter->asTime($query['dtr'], 'dd.MM.yyyy');
            unset($query['i_user']);
            $json = ['success' => 'yes', 'message' => 'Событие получено!', 'event' => $query ];
            die(json_encode($json));
        }
    }

    /*
     *
     *
     * */
    public function actionAdd()
    {
        //echo Debug::d($_SESSION,'session..');
        //echo Debug::d($_SERVER);
        $model = new Event();
        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()
            && $model->load(Yii::$app->request->post())
            )
        {
            $ev = $model; $ev->i_user = $_SESSION['user']['id'];
            if ( !$model->validate() ){
                $json = ['success' => 'no', 'message' => 'validate error!', 'error' => $model->errors];
                die(json_encode($json));
            }
            $ev->dtr = \Yii::$app->formatter->asTime($ev->dtr, 'yyyy-MM-dd'); # 14:09
            $rs = $ev->insert();
            if (!$rs) {
                $json = ['success' => 'no', 'message' => 'При добавлении события произошла ошибка!', 'err' => $rs];
                die(json_encode($json));
            }
            $q1 = (new Query)
                ->select("last_insert_id() as 'lid'")
                ->all();
            $ev = Event::find()->where(['i_user' => $_SESSION['user']['id'], 'id' => $q1[0]['lid']])->with('category')->one();

            // table row html
            switch ($ev->type){
                case 1: $evtype[1] = ['success', 'доход']; $evtypeid = 1;  break;
                case 2: $evtype[2] = ['danger',  'расход'];  $evtypeid = 2; break;
                default:$evtype[3] = ['type_undefined', 'просто событие']; $evtypeid = 3;
            }
            // ^ это старый код, в новом коде есть таблица с типами событий, из него и будем брать
            $real_type = Type::findOne($ev->type);
            if (!$real_type) { $r_type = 0; $r_color = 'fff'; }
            $r_type = $real_type->name; $r_color = $real_type->color;

            $mb_dt = mb_substr($ev->dtr,0,10);
            // new trh
            $trh = Event::getEventRowsStrByArray($ev->id,$ev->desc,$ev->summ,$mb_dt,
                //$evtype[$evtypeid][0], $evtype[$evtypeid][1],
                $r_type, $r_color,
                $ev['category']->name);
            $r1 = $ev;
            if ($r1) {
                $json = ['success' => 'yes', 'message' => 'Запись успешно добавлена!',
                    'post' => $r1,
                    'id' => $r1->id,
                    'desc' => $r1->desc,
                    'summ' => $r1->summ,
                    'type' => $r1->type,
                    'category' => $r1['category']['name'],
                    'trh' => $trh
                ];
                //echo Debug::d(json_encode($json));
            } else {
                $json = ['success' => 'no', 'message' => 'При добавлении записи произошла ошибка!'];
            }
            //$json = ['success' => 'middle', 'message' => 'this is a middle type of status'];
            die(json_encode($json));
        }else{
            $json = ['success' => 'no', 'message' => '---',];
            die(json_encode($json));
        }
    }

    /*
     *
     *
     * */
    public function actionDelete(){

        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {
            $uid = $_SESSION['user']['id'];
            $id = (int)Yii::$app->request->post('id');
            $res = Event::findOne(['id' => $id, 'i_user' => $uid]);
            $rs = $res->delete();
            if ($rs) {
                $json = ['success' => 'yes', 'message' => 'Запись успешно удалена!'];
            } else {
                $json = ['success' => 'no', 'message' => 'Запись НЕ удалена!'];
            }
            die(json_encode($json));
        }
        $json = ['success' => 'no', 'message' => '---', ];
        die(json_encode($json));
    }

    /*
     *
     *
     **/
    public function actionUpdate()
    {
        $model = new Event();
        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth())
        {
            $uid = $_SESSION['user']['id'];
            $model->id = $uid;
            $evid = Yii::$app->request->post('evid');
            $ev = Event::find()->where(['id' => $evid, 'i_user' => $uid ])
                ->with('types')->with('category')->one();
            if (!$ev) {
                $json = ['success' => 'no', 'message' => 'Запись не найдена!', 'err' => ''];
                die(($json));
            }
            //echo Debug::d($ev);
            $tmp['desc'] = Yii::$app->request->post('event-desc');
            $tmp['summ'] = Yii::$app->request->post('event-summ');
            $tmp['typeid'] = Yii::$app->request->post('event-typeid');
            $tmp['i_cat'] = Yii::$app->request->post('event-catid');
            $tmp['dtr'] = Yii::$app->request->post('event-date');
            $ev->desc = $tmp['desc'];
            $ev->summ = intval($tmp['summ']);
            $ev->type = intval($tmp['typeid']);
            $ev->i_cat = intval($tmp['i_cat']);
            $ev->dtr = $tmp['dtr'];
            $ev->dtr = Yii::$app->formatter->asTime($ev->dtr, 'yyyy-MM-dd');
            //
            if (!$ev->save()) {
                $json = ['success' => 'no', 'message' => 'Ошибка при обновлении записи!',
                    'tmp' => $tmp ];
                die(json_encode($json));
            }
            $rsu = Event::find()->where(['id' => $evid])->with('types')->with('category')->asArray()->one();
            if (!$rsu) {
                $json = ['success' => 'no', 'message' => 'Ошибка при получении обновленной записи!' ];
                die(json_encode($json));
            }
            $rsu['dtr'] = Yii::$app->formatter->asTime($rsu['dtr'], 'dd.MM.yyyy');

            $json = ['success' => 'yes', 'message' => 'Редактирование события завершено!', 'item' => $rsu];
            die(json_encode($json));
        }
        $json = ['success' => 'no', 'message' => 'Редактирование события завершено!', 'err' => $model->errors];
        die(json_encode($json));
    }

    /**
     *
     *
     */
    public function actionFilter(){
        if ((Yii::$app->request->isAjax) && AuthLib::appIsAuth()) {

            // event_type	1 2
            // event_cats	74 78
            // range1	20-12-2017
            // range2	30-12-2017

            $event_type = Yii::$app->request->get('event_type');
            // небольшой хак, чтобы получить 4 типа событий )
            // $event_type = '1 2 3 4';
            $ids_type = explode(' ',$event_type);
            $event_cats = Yii::$app->request->get('event_cats');
            $ids_cats = explode(' ',$event_cats);
            $event_range1 = Yii::$app->request->get('range1');
            $event_range2 = Yii::$app->request->get('range2');
            $evr1 = \Yii::$app->formatter->asTime($event_range1, 'dd.MM.yyyy');
            $evr2 = \Yii::$app->formatter->asTime($event_range2, 'dd.MM.yyyy');
            if (!$event_range1) { $event_range1 = date('d-m-Y'); $evr1 = $event_range1; }
            if (!$event_range2) { $event_range2 = date('d-m-Y'); $evr2 = $event_range2; }
            $event_range1 = \Yii::$app->formatter->asTime($event_range1, 'yyyy-MM-dd'); # 14:09
            $event_range2 = \Yii::$app->formatter->asTime($event_range2, 'yyyy-MM-dd'); # 14:09

            //echo Debug::d($event_type,'$event_type');
            //echo Debug::d($event_cats,'$event_cats');
            //echo Debug::d($event_range1,'$event_range1');
            //echo Debug::d($event_range2,'$event_range1');

            $query = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                ->andWhere(['in', 'i_cat', $ids_cats])
                ->andWhere(['in', 'type',  $ids_type])
                ->andWhere(['<>', 'summ',  0])
                ->orderBy(['type' => SORT_ASC, 'id' => SORT_ASC])
            ;
            //->asArray()->all();
            //echo Debug::d($query,'in weight');
            //$query = Event::find()->where(['i_user' => $_SESSION['user']['id']])->with('category');
            $q_counts = 500; // т.к. у нас идет поиск, мы должны захватить как можно больше в 1 странице,
            // тем более переход на 2-ю и более страницы не работает хД
            $pages = new Pagination(['totalCount' => $query->count(),'pageSize' => $q_counts,
                'pageSizeParam' => false, 'forcePageParam' => false, 'route' => 'site/history']);
            $rs = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

            $fl_count = count($rs);
            if ($fl_count){
                $pages_str = '';
                if ($pages !== '') { $pages_str = LinkPager::widget([ 'pagination' => $pages ]); }
                if (!$rs){
                    $json = ['success' => 'no', 'message' => 'Ошибка','rs' => [] ];
                    die(json_encode($json));
                }
                //
                $nrs = [];
                // table row html
                foreach($rs as $rsk => $ev){
                    $mb_dt = mb_substr($ev->dtr,0,10);
                    $trh = Event::getEventRowsStrByArray($ev->id,$ev->desc,$ev->summ,
                        $mb_dt,
                        $ev->types['name'],
                        $ev->types['color'],
                        $ev['category']->name);
                    $nrs[] = $trh;
                }
                // тут же мы должны получить даты начала и конца поиска, а также сумму расходов и доходов за этот период
                //
                $event_type = '1'; $ids_type = explode(' ',$event_type);
                $fl_dohody = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                    ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                    ->andWhere(['in', 'i_cat', $ids_cats])
                    ->andWhere(['in', 'type',  $ids_type])
                    ->andWhere(['<>', 'summ',  0])
                    //->all()
                    ->sum('summ')
                ;
                $event_type = '2'; $ids_type = explode(' ',$event_type);
                $fl_rashody = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                    ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                    ->andWhere(['in', 'i_cat', $ids_cats])
                    ->andWhere(['in', 'type',  $ids_type])
                    ->andWhere(['<>', 'summ',  0])
                    //->all()
                    ->sum('summ')
                ;
                $event_type = '3'; $ids_type = explode(' ',$event_type);
                $fl_dolgy = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                    ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                    ->andWhere(['in', 'i_cat', $ids_cats])
                    ->andWhere(['in', 'type',  $ids_type])
                    ->andWhere(['<>', 'summ',  0])
                    //->all()
                    ->sum('summ')
                ;
                $event_type = '4'; $ids_type = explode(' ',$event_type);
                $fl_vkladi = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                    ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                    ->andWhere(['in', 'i_cat', $ids_cats])
                    ->andWhere(['in', 'type',  $ids_type])
                    ->andWhere(['<>', 'summ',  0])
                    //->all()
                    ->sum('summ')
                ;
                //echo Debug::d($fl_dohody,'$fl_dohody');
                //echo Debug::d($fl_rashody,'$fl_rashody');
                $fl_dohody  = intval($fl_dohody);
                $fl_rashody = intval($fl_rashody);
                $fl_dolgy = intval($fl_dolgy);
                $fl_vkladi = intval($fl_vkladi);
                $fl_diff = abs($fl_dohody - $fl_rashody);
                if ($fl_rashody > $fl_dohody) {
                    $fl_diff *= (-1);
                }
                $summ_rdv = $fl_rashody + $fl_vkladi + $fl_dolgy;
                $diff_d_rdv = $fl_dohody - $summ_rdv;
                $summ_dv = $fl_vkladi + $fl_dolgy;
                $trs1 = <<<TRS1
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Сумма доходов</td><td><strong>{$fl_dohody}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS1;
                $trs2 = <<<TRS2
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Сумма расходов</td><td><strong>{$fl_rashody}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS2;
                $trs3 = <<<TRS3
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Разница доходы - расходы</td><td><strong>{$fl_diff}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS3;
                $trs4 = <<<TRS3
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Сумма долгов</td><td><strong>{$fl_dolgy}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS3;
                $trs5 = <<<TRS3
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Сумма вкладов</td><td><strong>{$fl_vkladi}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS3;
                $trs51 = <<<TRS3
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Сумма долгов и вкладов</td><td><strong>{$summ_dv}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS3;
                $trs6 = <<<TRS3
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Сумма расходов, долгов и вкладов</td><td><strong>{$summ_rdv}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS3;
                $trs7 = <<<TRS3
$('table.gg-history').append("<tr> <td colspan='3' style='text-align: right;'>Разница между доходами и тратами</td><td><strong>{$diff_d_rdv}</strong></td><td colspan='2'>{$evr1} - {$evr2}</td> <td></td> </tr>");
TRS3;
                $trs = [$trs1, $trs2, $trs3,$trs4,$trs5,$trs51,$trs6,$trs7]; $evr = [$evr1, $evr2];
                $json = ['success' => 'yes', 'message' => 'Фильт успешно отработал!','rs' => $nrs,
                    'pages' => $pages_str, 'trs' =>  $trs, 'evr' => $evr,
                ];


                die(json_encode($json));
            }

            $json = ['success' => 'no', 'message' => 'Фильт успешно отработал, но ничего не нашел!', 'count' => $fl_count ];
            die(json_encode($json));
        }
    }

}
