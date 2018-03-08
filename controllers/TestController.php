<?php

namespace app\controllers;

use app\components\Debug;
use app\models\Category;
use app\models\ContactForm;
use app\models\Type;
use app\models\Event;
use app\models\User;
use Yii;
use yii\db\Query;
//use moonland\phpexcel;
use PHPExcel;
use PHPExcel_IOFactory;
use DateTime;

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

//        echo \moonland\phpexcel\Excel::export([
//            'models' => $events,
//            'columns' => ['id','desc','summ'], //without header working, because the header will be get label from attribute label.
//            'headers' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
//        ]);

//        \moonland\phpexcel\Excel::widget([
//            'models' => $events,
//            'mode' => 'export', //default value as 'export'
//            'columns' => ['id','desc','summ'], //without header working, because the header will be get label from attribute label.
//            'headers' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
//        ]);

//        \moonland\phpexcel\Excel::export([
//            'models' => $events,
//            'columns' => [
//                'desc:text:Event desc',
//                'created_at:datetime',
//                [
//                    'attribute' => 'updated_at',
//                    'format' => 'date',
//                ],
//            ],
//            'headers' => [
//                'created_at' => 'Date Created Content',
//            ],
//        ]);



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
}
