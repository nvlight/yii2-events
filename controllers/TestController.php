<?php

namespace app\controllers;

use app\components\Debug;
use app\models\Category;
use app\models\ContactForm;
use app\models\Type;
use app\models\Event;
use app\models\User;
use app\models\User2;
use Yii;
use yii\db\Query;
use PHPExcel;
use PHPExcel_IOFactory;
use DateTime;
use yii\filters\AccessControl;

class TestController extends \yii\web\Controller
{
    //
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['r1', 'r2'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['r1'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['r2'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionR1(){
        echo __FUNCTION__;

    }
    public function actionR2(){
        echo __FUNCTION__;

    }

    public function actionUserlogin(){

        echo ' user login';

        //$username = 'ivanovich';
        //$identity = User::findOne(['uname' => $username]);
        $identity = User2::findIdentity(26);
        //echo Debug::d($identity,'identity');
        Yii::$app->user->login($identity);
        Yii::$app->user->logout();

        // `identity` текущего пользователя. `Null`, если пользователь не аутентифицирован.
        $identity = Yii::$app->user->identity;

        // ID текущего пользователя. `Null`, если пользователь не аутентифицирован.
        $id = Yii::$app->user->id;

        // проверка на то, что текущий пользователь гость (не аутентифицирован)
        $isGuest = Yii::$app->user->isGuest;

        echo Debug::d($identity,'identity',1);
        echo Debug::d($id,'identity',1);
        echo Debug::d($isGuest,'identity',1);


    }

    public function actionIndex()
    {
        $this->layout = '_main';
        // 1970-01-01T00:00:00Z
        //$publishedBefore = date('Y-m-d\Th:i:s\Z');
        $publishedBefore = '2017-03-21 10:20:12';
        if (Yii::$app->request->isGet && array_key_exists('publishedBefore', $_GET)){
            echo 'Da!';
            $publishedBefore = $_GET['publishedBefore'];
            $publishedBefore = Yii::$app->formatter->asDate($publishedBefore,'Y-m-d\Th:i:s\Z');
        }else{
            echo 'no!';
        }
        //echo $publishedBefore;

        return $this->render('index',['publishedBefore' => $publishedBefore]);
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

    /*
 *
 *
 **/
    public function actionLfile(){
        // nice joke )
        return \Yii::$app->response->sendFile('test/test.txt');
    }

    /*
     *
     *
     **/
    public function actionTmail(){
        // nice joke )
        $p[1] = 'iduso@mail.ru';
        $p[21] = Yii::$app->params['sw_frommail'];
        $p[22] = Yii::$app->params['name'];
        $p[3] = 'Events - регистрация'; // subject
        $p[4] = "Вы успешно зарегистрировались в приложении Events <br>\n\n";
        $dtReg = date("m.d.y H:i:s");
        $p[4] .= "Ваше имя: name<br>";
        $p[4] .= "Ваша почта: mail<br>";
        $p[4] .= "Ваш пароль: pass<br>";
        $p[4] .= "Дата регистрации: dt_reg<br>";
        $p[4] .= "<br/>Это сообщение отправлено автоматически, пожалуйста, не отвечайте на него<br/>";
        $res = Yii::$app->mailer->compose()
            ->setTo($p[1])
            ->setFrom([$p[21] => $p[22]])
            ->setSubject($p[3])
            ->setTextBody($p[4])
            ->send();
        echo 'done';
    }

    /*
     *
     *
     **/
    public function actionTmail2(){
        $p[1] = 'iduso@mail.ru';
        $p[21] = Yii::$app->params['sw_frommail'];
        $p[22] = Yii::$app->params['name'];
        $p[3] = "Events. Восстановление пароля";
        $p[4] = Html::a('Восстановить доступ!', ['user/do-restore?hash='.'reshash'], ['class' => 'btn btn-success']);
        $text_body = <<<TB
    <h4>Приложение Events</h4>
    <h5>Сброс пароля</h5>
    <p>Для того, чтобы сбросить пароль, нужно перейти по данной ссылке и получить временный пароль</p>
    <p>
       <a href="{11}"
        class="btn btn-success" target="_blank" rel="noopener" data-snippet-id="">
            {22}  
       </a>
    </p>
TB;
        $res = Yii::$app->mailer->compose('layouts/html',['content' => $text_body])
            ->setTo($p[1])
            ->setFrom([$p[21] => $p[22]])
            ->setSubject($p[3])
            ->setTextBody($text_body)
            ->send();
        echo 'res: ' . $res;
    }

    public function actionMail(){
        $this->layout = '@app/mail/layouts/html';

        return $this->render('mail', compact('mailData'));
    }

    public function actionExcelTest(){
        $start = 'chichin';
        //echo Debug::d($start,'$start',1);
        $events = Event::find()->where(['i_user' => $_SESSION['user']['id']])
            ->with('types')
            ->with('category')
            ->asArray()
            ->all();
        ;
        echo Debug::d($events,'events',1);


        // https://github.com/PHPOffice/PHPExcel
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        // Add some data
        for($i=0;$i<=10;$i++)
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //die(Debug::d( dba_handlers(),' dba_handlers()',1));

//        header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
//        header('Content-Disposition: attachment;filename="01simple.ods"');
//
//        $filename = sha1(md5((new DateTime())->format('r') ) . Yii::$app->params['file_export_salt'] ) . '.xlsx';
//        //echo $filename ; die;
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header("Content-Disposition: attachment;filename={$filename}");
//
//        header('Cache-Control: max-age=0');
//        // If you're serving to IE 9, then the following may be needed
//        header('Cache-Control: max-age=1');
//
//        // If you're serving to IE over SSL, then the following may be needed
//        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
//        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//        header ('Pragma: public'); // HTTP/1.0
//
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'OpenDocument');
//        $objWriter->save('php://output');
//        exit;

        //$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        //$writer->save('php://output');

//         its work
//        $fileName = 'tt.xlsx';
//        $data = \moonland\phpexcel\Excel::import($fileName);
//        echo Debug::d($data,'data');

    }

    /*
     *
     *
     * */
    public function actionModelinfo(){
        $event = (new Event())->attributeLabels();
        unset($event['i_user'],$event['id'],$event['dt'],$event['note']);
        $event = array_merge(['id' => 'id'], $event);
        echo Debug::d($event,'$event', 1);

        $i = 0; $ik = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        foreach($event as $ev => $ek){
            $i++; // substr($ik,$i-1,1)
            echo "i: $i " . $ik[$i-1] . ' ev: ' . $ek . "<br>";
        }

        //
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $arr = [];
        for($i=0;$i<strlen($str);$i++){
            $arr[] = substr($str,$i,1);
        }
        echo Debug::d($arr,'arr',1);

        //echo DateTime::createFromFormat('d.m.yyyy h:i:s');
        $curr = (new DateTime())->format('d.m.Y h:i:s');
        echo $curr;
    }

    /*
     *
     *
     * */
    public function actionAr(){
        echo 'start active record';
        echo "<br>";
        // возвращает всех покупателей массивом, индексированным их идентификаторами
        // SELECT * FROM `customer`
        $Event = Event::find()
            ->where(['>','id',0])
            ->one();
        //echo Debug::d($Event,'Event');
        echo "<pre>";
        print_r($Event);
        echo "</pre>";
        $Event = Event::find()
            ->indexBy('id')
            ->one();
        //echo Debug::d($Event,'Event');
    }

    public function actionDb(){

        // возвращает набор строк. каждая строка - это ассоциативный массив с именами столбцов и значений.
        // если выборка ничего не вернёт, то будет получен пустой массив.
        //$posts = Yii::$app->db->createCommand('SELECT * FROM video')->queryAll();
        //echo Debug::d($posts,'posts 1');

        // вернёт одну строку (первую строку)
        // false, если ничего не будет выбрано
        //$post = Yii::$app->db->createCommand('SELECT * FROM video WHERE id=19')->queryOne();
        //echo "<pre>"; print_r($post); echo "</pre>";
        //echo Debug::d($post,'posts 2');

        // вернёт один столбец (первый столбец)
        // пустой массив, при отсутствии результата
        //$titles = Yii::$app->db->createCommand('SELECT title FROM video')->queryColumn();
        //echo Debug::d($titles,'posts 3');
        // вернёт скалярное значение
        // или false, при отсутствии результата
        //$count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM video')->queryScalar();
        //echo Debug::d($count,'posts 4');

        /// ### next stage - using построитель запросов
        $q = (new Query())->select('video.id, video.i_user, i_cat, title,url,video_id,active,channelid,channeltitle,categoryvideo.name as catname')
            ->from('video')
            ->where(['>=','video.id',0]);
        $q = $q->andWhere('active=:active');
        $q = $q->addParams([':active' => 1]);
        $q = $q->join('left join','categoryvideo','video.i_cat = categoryvideo.id');
        $q = $q->indexBy(function ($row) {return $row['video_id'];});
        $q = $q->limit(2)->offset(1);
        $qd = $q->createCommand();
        $rs = $q->all();
        //$rs = $q->one();
        //$rs = $q->count();
        echo Debug::d($rs,'rs',2);
        echo Debug::d($qd->sql,'$qd->query',1);
        echo Debug::d($qd->params,'$qd->params',1);
        //echo Debug::d($qd->queryAll(),'$qd->queryAll()',1);

        //$q2 = (new Query())->select('')->from('user,type')->all();
        //echo Debug::d($q2,'q2');



    }


    public function actionTestJs1(){
        echo 'testJs1';

        echo Debug::d($_GET,'get');
    }

}
