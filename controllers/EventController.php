<?php

namespace app\controllers;

use app\components\AuthLib;
use app\models\Category;
use app\models\Event;
use yii\data\Pagination;
use Yii;
use yii\db\Query;
use app\models\Type;
use yii\widgets\LinkPager;
use app\components\Debug;
use PHPExcel;
use PHPExcel_IOFactory;
use DateTime;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class EventController extends \yii\web\Controller
{
    //
    public function actionError(){
        $exception = Yii::$app->errorHandler->exception;
        $statusCode = $exception->statusCode;
        $name = $exception->getName();
        $message = $exception->getMessage();
        $this->layout = false;
        return $this->render('@app/views/event/error', [
            'exception' => $exception,
            'statusCode' => $statusCode,
            'name' => $name,
            'message' => $message
        ]);
    }

    /**
     *
     *
     **/
    public function actionHistory($sortcol='dtr',$sort='desc'){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $this->layout = '_main';
        $getEvents = Event::getHistory($sortcol,$sort);
        //echo Debug::d($getEvents,'getEvents',1); die;
        return $this->render('history',
            ['events' => $getEvents[0],'pages' => $getEvents[1],'sort' => $getEvents[2],'ev2' => $getEvents[3] ]
        );
    }

    /*
     * Without JS - show
     *
     * */
    public function actionShow($id){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        if (Yii::$app->request->method === 'GET')
        {
            $rs = Event::find()->where(['id' => $id, 'i_user' => $_SESSION['user']['id']])
                ->with('category')->with('types')->asArray()->one();
            if (!$rs) throw new HttpException(404 ,'События с таким ID не найдено');

            $this->layout = '_main';
            return $this->render('show', compact('rs'));
        }
    }

    /**
     * Without JS - upd
     *
     * */
    public function actionUpd($id){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        if (Yii::$app->request->method === 'GET'){
            $rs = Event::find()->where(['id' => $id, 'i_user' => $_SESSION['user']['id']])
                ->with('category')->with('types')//->asArray()
                ->one();
            if (!$rs) throw new HttpException(404 ,'События с таким ID не найдено');
            $model = $rs;
            $this->layout = '_main';
            return $this->render('update',compact('model'));
        } elseif
            (Yii::$app->request->method === 'POST') {

            $model =  Event::find()->where(['id' => $id, 'i_user' => $_SESSION['user']['id']])->one();
            if ( $model && $model->load(Yii::$app->request->post())) {
                $model->i_user = $_SESSION['user']['id'];
                //
                if ( $model->validate() && $model->save() ){
                    return $this->redirect(['show', 'id' => $model->id]);
                }
            }
            //
            $this->layout = '_main';
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Without JS - create
     *
     * */
    public function actionCreate(){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        //
        $model = new Event();
        $model->summ = 0;
        $model->dtr = date('Y-m-d');
        $this->layout = '_main';
        if ($model->load(Yii::$app->request->post())) {
            $model->i_user = $_SESSION['user']['id'];
            if ( $model->validate() &&  $model->save()){
                return $this->redirect(['show', 'id' => $model->id]);
            }
            return $this->render('update',  [
                'model' => $model,
            ]);
        }
        return $this->render('update',  [
            'model' => $model,
        ]);

    }

    /*
     * Without JS - del
     *
     **/
    public function actionDel(){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        if (Yii::$app->request->method === 'GET'){
            $id = Yii::$app->request->get('id'); $rs = null;
            //echo $id; die;
            if ( preg_match("#^[1-9]\d{0,7}$#", $id) &&
                Event::find()->where(['id' => $id, 'i_user' => $_SESSION['user']['id']]))
            {
                $res = Event::findOne(['id' => $id, 'i_user' => $_SESSION['user']['id']]);
                $rs = $res->delete();
                if ($rs) {
                    Yii::$app->session->setFlash('delEvent','Запись удалена!');
                }
            }else{
                throw new HttpException(404 ,'События с таким ID не найдено');
            }
            //$this->layout = '_main';
            return $this->redirect(['event/history']);
        }
    }

    /*
     * AJAX
     **/
    public function actionGet($id){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        if ((Yii::$app->request->isAjax)) {
            $query = Event::find()->where(['i_user' => $_SESSION['user']['id'], 'id' => $id ])
                ->with('category')->with('types')->asArray()->one();
            //echo Debug::d($query,'query');
            if ($query){
                $query['dtr'] = \Yii::$app->formatter->asTime($query['dtr'], Yii::$app->formatter->dateFormat);
                unset($query['i_user']);
                $json = ['success' => 'yes', 'message' => 'Событие получено!', 'event' => $query ];
            }else{
                $json = ['success' => 'no', 'message' => 'Событие не получено!' ];
            }
            die(json_encode($json));
        }
    }

    /*
     *
     *
     **/
    public function actionAdd()
    {
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        //
        $model = new Event();
        if ((Yii::$app->request->isAjax)
            && $model->load(Yii::$app->request->post())
            )
        {
            $ev = $model; $ev->i_user = $_SESSION['user']['id'];
            if ( !$ev->summ) $ev->summ = 0;
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
            //$q1 = (new Query)->select("last_insert_id() as 'lid'")->all();
            //$ev = Event::find()->where(['i_user' => $_SESSION['user']['id'], 'id' => $q1[0]['lid']])
            //    ->with('category')->with('types')->one();
            $mb_dt = mb_substr($ev->dtr,0,10);
            $trh = Event::getEventRowsStrByArray($ev->id,$ev->desc,$ev->summ,
                $mb_dt, $ev->types['name'], $ev->types['color'], $ev['category']->name);

            $json = ['success' => 'yes', 'message' => 'Запись успешно добавлена!','trh' => $trh];
            die(json_encode($json));
        }
    }

    /*
     *
     *
     **/
    public function actionDelete(){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        //
        if ((Yii::$app->request->isAjax)) {
            $res = Event::findOne(['id' => Yii::$app->request->post('id'), 'i_user' => $_SESSION['user']['id']]);
            $rs = $res->delete();
            if ($rs) {
                $json = ['success' => 'yes', 'message' => 'Запись успешно удалена!'];
            } else {
                $json = ['success' => 'no', 'message' => 'Запись НЕ удалена!'];
            }
            die(json_encode($json));
        }
    }

    /*
     *
     *
     **/
    public function actionUpdate()
    {
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $model = new Event();
        if ((Yii::$app->request->isAjax))
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
            try {
                $ev->dtr = Yii::$app->formatter->asDatetime($ev->dtr, 'yyyy-MM-dd');
            }catch (\Exception $e){
                $ev->dtr = date('d-m-Y');
            }
            //
            if ( !$ev->validate() || !$ev->save()) {
                $json = ['success' => 'no', 'message' => 'Ошибка при обновлении записи!',
                    'tmp' => $tmp, 'errors' => $ev->errors ];
                die(json_encode($json));
            }
            // нужно найти только что вставленный элемент, чтобы аяксом обновить строку в таблице...
            $rsu = Event::find()->where(['id' => $evid, 'i_user' => $uid])
                ->with('types')->with('category')->asArray()->one();
            if (!$rsu) {
                $json = ['success' => 'no', 'message' => 'Ошибка при получении обновленной записи!' ];
                die(json_encode($json));
            }
            $rsu['dtr'] = Yii::$app->formatter->asTime($rsu['dtr'], Yii::$app->formatter->dateFormat);

            $json = ['success' => 'yes', 'message' => 'Редактирование события завершено!', 'item' => $rsu];
            die(json_encode($json));
        }
    }

    /*
     *
     *
     **/
    public function actionTestq(){

        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $colName = 'type.name'; $text = 'вкл';
        $rs = Event::find()->where(['event.i_user' => $_SESSION['user']['id']])
            ->andWhere(['like',$colName,$text])
            ->joinWith('types') //->asArray()
            ->limit(50)->all();
        echo Debug::d(count($rs),'count rs');
        echo Debug::d($rs,'rs');
    }

    /**
     *
     *
     **/
    public function actionSearchByColval($idCol=1,$text=''){
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        if ((Yii::$app->request->isAjax)) {

            // params: idCol - text
            // cat,summ,dtr,type
            switch ($idCol){
                case 2: {
                    $colName = 'summ';
                    $text = (string)intval(($text));
                    $rs = Event::find()->where(['i_user' => $_SESSION['user']['id'] ]) //,$colName => $text
                        ->andWhere(['like',$colName,$text])
                        ->with('category')->with('types')->limit(50)->all();
                    break;
                }
                case 3: {
                    $colName = 'dtr';
                    $rs = Event::find()->where(['i_user' => $_SESSION['user']['id']])
                        ->with('category')->with('types')
                        ->andWhere(['like',$colName,$text])
                        ->limit(50)->all();
                    break;
                }
                case 4: {
                    $colName = 'type.name'; // $text = 'расх';
                    $rs = Event::find()->where(['event.i_user' => $_SESSION['user']['id']])
                        ->andWhere(['like',$colName,$text])
                        ->joinWith('types') //->asArray()
                        ->limit(50)->all();
                    break;
                }
                case 7: {
                    $colName = 'desc';
                    $rs = Event::find()->where(['i_user' => $_SESSION['user']['id']])
                        ->with('category')->with('types')
                        ->andWhere(['like',$colName,$text])
                        ->limit(50)->all();
                    break;
                }
                case 6:{
                    $pgs = 'default'; $colName = 'desc';
                    $q_counts = Yii::$app->params['history_post_count'];
                    $query = Event::find()->where(['i_user' => $_SESSION['user']['id']])
                        ->with('category')->with('types');
                    // пагинация работает, но немножко не так, нужно чтобы было как на хистори после обновления.
                    // а сейчас, все есть на странице, правда в обратном порядке )
                    $pages = new Pagination(['totalCount' => $query->count(),'pageSize' => $q_counts,
                        'pageSizeParam' => false, 'forcePageParam' => false, 'route' => 'event/history' ]);
                    $rs = $query->offset($pages->offset)
                        ->orderBy(['id' => SORT_DESC])->limit($pages->limit)->all();
                    break;
                }
                default:{
                    $colName = 'category.name';
                    $rs = Event::find()->where(['event.i_user' => $_SESSION['user']['id']])
                        ->andWhere(['like',$colName,$text])
                        ->joinWith('category')->limit(50)->all();
                }

            }

            if (!$rs){
                $json = ['success' => 'no', 'message' => 'Ошибка','rs' => [] ];
                die(json_encode($json));
            }

            $pages_str = '';
            if (isset($pgs) && $pgs === 'default') {
                $pages_str = LinkPager::widget([ 'pagination' => $pages ]);
            }

            // table row html
            $nrs = [];
            foreach($rs as $rsk => $ev){
                $mb_dt = mb_substr($ev->dtr,0,10);
                $trh = Event::getEventRowsStrByArray($ev->id,$ev->desc,$ev->summ,
                    $mb_dt,
                    $ev->types['name'],
                    $ev->types['color'],
                    $ev['category']->name);
                $nrs[] = $trh;
            }
            $json = ['success' => 'yes', 'message' => 'Успех','rs0' => $rs, 'rs' => $nrs, 'pages' => $pages_str];
            die(json_encode($json));
        }
    }

    /**
     *
     *
     */
    public function actionConvertToXslx()
    {
        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        // подготовка данных и его конвертация
        $eventLabels = (new Event())->attributeLabels();
        unset($eventLabels['i_user'],$eventLabels['id'],$eventLabels['dt'],$eventLabels['note']);
        $eventLabels = array_merge(['id' => '№'], $eventLabels);

        // https://github.com/PHPOffice/PHPExcel
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw - yeah")
            ->setLastModifiedBy("Maarten Balliauw - yeah")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $events = Event::find()->where(['i_user' => $_SESSION['user']['id']])
            ->with('types')
            ->with('category')
            ->asArray()
            ->all();
        ;

        // add new data
        // prepare columns
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $arr = [];
        for($i=0;$i<strlen($str);$i++){
            $arr[] = substr($str,$i,1);
        }
        // add current datetime - row
        $j = 1; $currDt = (new DateTime())->format('d.m.Y h:i:s');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($arr[0] . $j, 'Дата: ')
            ->setCellValue($arr[1] . $j, $currDt);
        // add rows count - row
        $j++; $rowCount = count($events); // $j = 2
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($arr[0] . $j, 'Количество строк: ')
            ->setCellValue($arr[1] . $j, $rowCount);

        // add table labels ...
        $i = 0; $j++; // j = 3
        foreach($eventLabels as $ev => $ek){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($str[$i] . $j, $ek);
            $i++;
        }
        // add anather rows
        $j++; $i=1; // j = 4
        foreach($events as $ek => $ev){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($arr[0] . $j, $i)
                ->setCellValue($arr[1] . $j, $ev['category']['name'])
                ->setCellValue($arr[2] . $j, $ev['desc'])
                ->setCellValue($arr[3] . $j, $ev['summ'])
                ->setCellValue($arr[4] . $j, $ev['dtr'])
                ->setCellValue($arr[5] . $j, $ev['types']['name']);
            $j++; $i++;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //die(Debug::d( dba_handlers(),' dba_handlers()',1));

        $filename = sha1(md5((new DateTime())->format('r') ) . Yii::$app->params['file_export_salt'] ) . '.xlsx';
        //echo $filename ; die;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename={$filename}");

        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'OpenDocument');
        $objWriter->save('php://output');
        exit;
    }

    /*
     *
     *
     **/
    public function actionPlan(){

        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $subquery1 = (new \yii\db\Query)->select('summ')->from('event')->where(['=','type','1'])->andWhere(['=','category.id','event.i_cat'])->limit(1);
        //$subquery1 = (new \yii\db\Query)->select('summ')->from('event')->where(['=','type','1'])->limit(1);
        $subquery2 = (new \yii\db\Query)->select('summ')->from('event')->where(['=','type','2'])->limit(1);
        $query = (new \yii\db\Query)->select(['name', 'c1' => $subquery1,'c2' => $subquery2])->from('category');
        $res =  $query->all();
        //echo Debug::d($res);

        //$q1 = Category::find()->select(['name'])->where(['>','id',1])->with('event')->all();
        $q1 = (new Query)
            ->select(['category.name'
                //,'event.type',
                ," @p11 := (select abs(SUM(event.summ)) from event WHERE event.i_cat = category.id and event.type = 1) as 'p11'"
                ," @p12 := (select abs(SUM(event.summ)) from event WHERE event.i_cat = category.id and event.type = 2) as 'p12'"
                ," @p1 := (abs(@p11 - @p12) + 0) as 'p1' "
                , "category.`limit` as 'p2'"
            ])
            ->from('category')->where(['i_user' => $_SESSION['user']['id']])
            ->all();
        //echo Debug::d($q1);

        // большая часть, написанная выше, сделано напросно, т.к. мы будем считать только расходы, а не их разность
        // получим здесь $remains - $all_rashod...
        $remains = $_SESSION['user']['remains'];
        $all_rashod = 0; foreach ($q1 as $qk => $qv) { $all_rashod += $qv['p12']; } //echo $qv['p12'] . "</br>"; }
        $diff_main = $remains - $all_rashod;

        // получили тут разность расходов с доходами и остаток - массив
        $catPlans = $q1;
        $this->layout = '_main';
        return $this->render('plan', compact('catPlans','remains','diff_main'));
    }

    /**
     *
     *
     **/
    public function actionSs($sortColumn='id',$sortType=SORT_DESC){

        echo 'ss';
    }

    /**
     *
     *
     **/
    public function actionSimpleFilter($sortColumn='id',$sortType=SORT_DESC){

        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        if (Yii::$app->request->isGet or Yii::$app->request->isAjax){
            $this->layout = '_main';

            //echo Debug::d($_REQUEST,'request');
            if (
                !(
                    (
                        array_key_exists('range1',$_GET) &&
                        array_key_exists('range2',$_GET)
                    )
                        &&
                    (
                        (
                            array_key_exists('cat',$_GET) ||
                            (
                                array_key_exists('Event',$_GET) &&
                                is_array($_GET['Event']) &&
                                array_key_exists('i_cat',$_GET['Event']) &&
                                is_array($_GET['Event']['i_cat']) &&
                                count($_GET['Event']['i_cat'])
                            )
                        )
                            &&
                        (
                            array_key_exists('type',$_GET) ||
                            (
                                array_key_exists('Event',$_GET) &&
                                is_array($_GET['Event']) &&
                                array_key_exists('type',$_GET['Event']) &&
                                is_array($_GET['Event']['type']) &&
                                count($_GET['Event']['type'])
                            )

                        )
                    )

                )
               )
            {
                //
                if (Yii::$app->request->isAjax){
                    $json = ['success' => 'no', 'message' => 'Недостаточно входных параметров1','rs' => $_GET ];
                    die(json_encode($json));
                }elseif(Yii::$app->request->isGet){
                    $json = ['success' => 'no', 'message' => 'Недостаточно входных параметров2','rs' => [] ];
                    return $this->render('simplefilter', compact('json'));
                }
                //$json = ['success' => 'no', 'message' => 'Ошибка-1','rs' => [] ];
                //return $this->render('simplefilter', compact('json'));
            }
            //echo Debug::d($_GET,'get'); die;

            // zadacha 1 получаем ключ сортировки или ставим по умолчанию
            // stage 1
            $orderBy = [$sortColumn => $sortType];
            // stage 2
            $ev_tableSchema = Event::getTableSchema()->columnNames;
            if (array_key_exists('sortType',$_GET) && array_key_exists('sortColumn',$_GET)){
                $sortType = intval($_GET['sortType']);
                //echo $sortType;
                // если сортТайм придуман от балды, выставляем 3 или же SORT_DESC
                if (($sortType !== 3) && ($sortType !== 4)){
                    $sortType = 4;
                }
                if ( in_array($_GET['sortColumn'],$ev_tableSchema)){
                    $sortColumn = $_GET['sortColumn'];
                    $orderBy[$sortColumn] = ($sortType === SORT_ASC) ? SORT_ASC : SORT_DESC;
                }
            }
            // end of zadacha 1

            // формируем строку запроса из гет-параметров + параметра сортировки
            $buildHttpQuery = http_build_query($_GET);
            //echo Debug::d($buildHttpQuery,'httpBuildQuery',3);

            // если $_GET['type'] && $_GET['cat'] есть, то нужно выбрать все типы и все категории !
            //!array_key_exists('cat',$_GET) &&
            //!array_key_exists('type',$_GET)
            // #stage 1
            $type_checked_all = $cats_checked_all = null;
            if (array_key_exists('type',$_GET)){
                $types = Type::find()->where(['>','id',0])->andWhere(['i_user' => $_SESSION['user']['id']])->asArray()->all();
                $event_type = []; $type_checked_all = 1;
                foreach($types as $tk => $tv) $event_type[] = $tv['id'];
                //echo Debug::d($types,'types');
                //echo Debug::d($event_type,'$event_type');
            }else{
                $event_type = Yii::$app->request->get('Event')['type'];
            }
            // #stage 2
            if (array_key_exists('cat',$_GET)){
                $cats = Category::find()->where(['>','id',0])->asArray()->all();
                $event_cats = []; $cats_checked_all = 1;
                foreach($cats as $tk => $tv) $event_cats[] = $tv['id'];
                //echo Debug::d($cats,'types');
                //echo Debug::d($event_cats,'$event_cats');
            }else{
                $event_cats = Yii::$app->request->get('Event')['i_cat'];
            }

            $ids_type2 = $ids_type = $event_type;
            $ids_cats2 = $ids_cats = $event_cats;
            //echo Debug::d($ids_type2,'$ids_type2');

            $event_range1 = Yii::$app->request->get('range1');
            $event_range2 = Yii::$app->request->get('range2');
            // validate date and set default values
            try {
                Yii::$app->formatter->asDatetime($event_range1, "d.m.Y");
            }catch (\Exception $e){
                $event_range1 = date('d-m-Y');
            }
            try {
                Yii::$app->formatter->asDatetime($event_range2, "d.m.Y");
            }catch (\Exception $e){
                $event_range2 = date('d-m-Y');
            }

            $evr1 = \Yii::$app->formatter->asTime($event_range1, Yii::$app->formatter->dateFormat);
            $evr2 = \Yii::$app->formatter->asTime($event_range2, Yii::$app->formatter->dateFormat);

            $event_range1 = \Yii::$app->formatter->asTime($event_range1, 'yyyy-MM-dd'); # 14:09
            $event_range2 = \Yii::$app->formatter->asTime($event_range2, 'yyyy-MM-dd'); # 14:09

            //
            $query = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                ->andWhere(['in', 'i_cat', $ids_cats])
                ->andWhere(['in', 'type',  $ids_type]);
            //
            if (!array_key_exists('zero_summ',$_GET)){
                $query = $query->andWhere(['<>', 'summ',  0]);
            }else{
                $query = $query->andWhere(['>=', 'summ',  0]);
            }
            $query = $query->orderBy($orderBy) // ['type' => SORT_ASC, 'id' => SORT_ASC]
                // ->asArray()
                //->all();
            ;
            //echo Debug::d($query,'in weight');
            $q_counts = 50;
            $q_counts = Yii::$app->params['history_post_search'];
            $pages = new Pagination(['totalCount' => $query->count(),'pageSize' => $q_counts,
                'pageSizeParam' => false, 'forcePageParam' => false,  ]);
            $rs = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
            //echo Debug::d($rs,'rs');

            if (!$rs){
                $json = ['success' => 'no', 'message' => 'Ошибка-2','rs' => [] ];
                //die(json_encode($json));
                return $this->render('simplefilter', compact('json'));
            }

            // тут же мы должны получить даты начала и конца поиска, а также сумму расходов и доходов за этот период
            $ev_tps = ['доходы' => 1,'расходы' => 2,'долги' => 3,'вклады' => 4,];
            $ev_res = [];
            foreach($ev_tps as $evk => $evv){
                $new_q =  Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                    ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                    ->andWhere(['in', 'i_cat', $ids_cats])
                    ->andWhere(['<>', 'summ',  0]);
                $new_q = $new_q->andWhere(['in', 'type',  [$evv]]);
                //echo Debug::d($new_q->where);
                $ev_res[] = $new_q->sum('summ');
            }
            //echo Debug::d($ev_res,'ev_res');

            $fl_dohody  = intval($ev_res[0]);
            $fl_rashody = intval($ev_res[1]);
            $fl_dolgy = intval($ev_res[2]);
            $fl_vkladi = intval($ev_res[3]);
            $fl_diff = abs($fl_dohody - $fl_rashody);
            if ($fl_rashody > $fl_dohody) {
                $fl_diff *= (-1);
            }
            $summ_rdv = $fl_rashody + $fl_vkladi + $fl_dolgy;
            $diff_d_rdv = $fl_dohody - $summ_rdv;
            $summ_dv = $fl_vkladi + $fl_dolgy;
            $dt_diff = "{$evr1} - {$evr2}";
            $trs[] = ['Сумма доходов', $fl_dohody,$evr1,$evr2];
            $trs[] = ['Сумма расходов',$fl_rashody,$evr1,$evr2];
            $trs[] = ['Разница доходы - расходы', $fl_diff,$evr1,$evr2];
            $trs[] = ['Сумма долгов', $fl_dolgy,$evr1,$evr2];
            $trs[] = ['Сумма вкладов', $fl_vkladi,$evr1,$evr2];
            $trs[] = ['Сумма долгов и вкладов', $summ_dv,$evr1,$evr2];
            $trs[] = ['Сумма расходов, долгов и вкладов', $summ_rdv,$evr1,$evr2];
            $trs[] = ['Разница между доходами и тратами', $diff_d_rdv,$evr1,$evr2];
            $json = [
                'success' => 'yes', 'message' => 'Фильт успешно отработал!',
                'rs' => $rs,
                'pages' => $pages,
                'trs' =>  $trs,
                'buildHttpQuery' => $buildHttpQuery,
                'orderBy' => $orderBy,
                'dt_diff' => $dt_diff,
                'ids_cats' => $ids_cats2,
                'ids_type' => $ids_type2,
                'type_checked_all' => $type_checked_all,
                'cats_checked_all' => $cats_checked_all,
                'evr1' => $evr1,
                'evr2' => $evr2,
            ];
            //die(json_encode($json));
            return $this->render('simplefilter', compact('json'));
        }
    }

    /**
     *
     *
     */
    public function actionFilter(){

        //
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        if ((Yii::$app->request->isAjax)) {

            $event_type = Yii::$app->request->get('event_type');
            // небольшой хак, чтобы получить 4 типа событий )
            // $event_type = '1 2 3 4';
            $ids_type = explode(' ',$event_type);
            $event_cats = Yii::$app->request->get('event_cats');
            $ids_cats = explode(' ',$event_cats);
            $event_range1 = Yii::$app->request->get('range1');
            $event_range2 = Yii::$app->request->get('range2');
            $evr1 = \Yii::$app->formatter->asTime($event_range1, Yii::$app->formatter->dateFormat);
            $evr2 = \Yii::$app->formatter->asTime($event_range2, Yii::$app->formatter->dateFormat);
            if (!$event_range1) { $event_range1 = date('d-m-Y'); $evr1 = $event_range1; }
            if (!$event_range2) { $event_range2 = date('d-m-Y'); $evr2 = $event_range2; }
            $event_range1 = \Yii::$app->formatter->asTime($event_range1, 'yyyy-MM-dd'); # 14:09
            $event_range2 = \Yii::$app->formatter->asTime($event_range2, 'yyyy-MM-dd'); # 14:09

            $query = Event::find()->where(['i_user' => $_SESSION['user']['id'],])->with('category')
                ->andwhere(['between', 'dtr', $event_range1, $event_range2 ])
                ->andWhere(['in', 'i_cat', $ids_cats])
                ->andWhere(['in', 'type',  $ids_type])
                ->andWhere(['<>', 'summ',  0])
                ->orderBy(['id' => SORT_DESC])
                //->asArray()
                //->all()
            ;
            //echo Debug::d($query,'in weight'); die;
            //echo Debug::d($query,'in weight');
            //$query = Event::find()->where(['i_user' => $_SESSION['user']['id']])->with('category');
            $q_counts = 500; // т.к. у нас идет поиск, мы должны захватить как можно больше в 1 странице,
            // тем более переход на 2-ю и более страницы не работает хД
            $pages = new Pagination(['totalCount' => $query->count(),'pageSize' => $q_counts,
                'pageSizeParam' => false, 'forcePageParam' => false]); // 'route' => 'site/history'
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
