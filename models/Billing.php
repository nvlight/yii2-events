<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 07.05.2018
 * Time: 10:20
 */

namespace app\models;

use app\components\Debug;
use Yii;
use yii\base\Model;
//use yii\helpers\Html;
//use yii\helpers\ArrayHelper;
use yii\helpers\{Html, ArrayHelper};
use yii\web\Controller;
use DateTime;

class Billing extends Model
{
    //
    public static function getCourses(){

        $res = ['success' => 'no', 'message' => 'data not recived'];
        $filename = './courses.json';
        if (!is_file($filename)) {
            //echo 'weeaw';
            try {
                // http://www.cbr.ru/scripts/XML_daily.asp
                // https://www.cbr-xml-daily.ru/daily_json.js
                $url = 'https://www.cbr-xml-daily.ru/daily_json.js';
                $nd = file_get_contents($url);
                if ($nd) {
                    file_put_contents($filename, $nd);
                }
            } catch (\Exception $e) {

            }
        }
        if ($nd = @file_get_contents($filename)){
            $res = [
                'success' => 'yes',
                'message' => 'data has been recived',
                'rs' => json_decode($nd,1)
            ];
        }

        return $res;
    }

    //
    public function updateCourses(){

        $res = false;
        $filename = './courses.json';
        try {
            // http://www.cbr.ru/scripts/XML_daily.asp
            // https://www.cbr-xml-daily.ru/daily_json.js
            $url = 'https://www.cbr-xml-daily.ru/daily_json.js';
            $nd = file_get_contents($url);
            if ($nd) {
                file_put_contents($filename, $nd);
                $res = true;
            }
        }catch (\Exception $e){

        }
        if ($res){
            Yii::$app->session->setFlash('courses','Данные курсов валют были обновлены');
        }
        return;
    }

    //
    public static function getCoursesI()
    {
        $course_local_fn = './courses.json';
        $url4parse = 'https://www.cbr-xml-daily.ru/daily_json.js';
        if (!@ file_get_contents($course_local_fn) ){

            $url_inner = @file_get_contents($url4parse);
            $inner_decode = json_decode($url_inner,1);
            $inner_decode['my_date'] = Date('Y-m-d');
            $url_inner = json_encode($inner_decode);
            @file_put_contents($course_local_fn, $url_inner);
            Yii::$app->session->setFlash('courses','Данные курсов валют были обновлены');
            echo json_encode(['rs' => $url_inner, 'success' => 'yes']);
            die;
        }

        //
        if (is_file($course_local_fn) ){
            $courses = file_get_contents($course_local_fn);
            //echo Debug::d(json_decode($courses,1));
            $courses = json_decode($courses,1);
            // тут же проверим, свежая ли дата, т.е. дата не сегоднешняя, то
            $timestamp = $courses['Timestamp'];
            $course_parse_datatime = Yii::$app->formatter->asDatetime($timestamp,'Y-MM-dd');
            //echo $course_parse_datatime; echo '<br>';
            // тут введен параметр my_date, чтобы лучше контролировать дату последнего изменения
            $course_parse_datatime = $courses['my_date'];

            $curr_datatime  = Date('Y-m-d');
            //$curr_datatime = '2018-12-19';
            //echo $curr_datatime; echo '<br>';
            $diff = abs(strtotime($curr_datatime) - strtotime($course_parse_datatime));
            // echo 'diff in seconds: ' . $diff;
            $day_diff = $diff /  ( 3600 * 24 );
            if ($day_diff >= 1){
                // update our course with newest!
                //
                // echo 'So! we are here!'; die;
                $url_inner = @file_get_contents($url4parse);
                //echo json_encode(['success' => 'yes', 'rs' => $url_inner ]); die;
                $inner_decode = json_decode($url_inner,1);
                $inner_decode['my_date'] = Date('Y-m-d');
                $url_inner = json_encode($inner_decode);
                //echo $url_inner; die;
                @file_put_contents($course_local_fn, $url_inner);
                Yii::$app->session->setFlash('courses','Данные курсов валют были обновлены');
                echo json_encode(['rs' => $url_inner, 'success' => 'yes']);
                die;
            }

            //
            if (is_array($courses) && array_key_exists('Valute', $courses)){
                //
                echo json_encode(['rs' => '', 'success' => 'yes']);
                die;
            }
        }
        echo json_encode(['rs' => [], 'success' => 'no']); die;
    }

    //
    public static function getCoursesCurrent()
    {
        $course_local_fn = './courses.json';
        if ( !is_file($course_local_fn))
        {
            return ['rs' => [], 'success' => 'no'];
        }
        $courses = file_get_contents($course_local_fn);
        //echo Debug::d($courses);
        //$inner_decode = json_decode($courses,1);
        //$inner_decode['my_date'] = Date('Y-m-d');
        //$courses = json_encode($inner_decode);
        $courses = json_decode($courses,1);
        return ['rs' => $courses, 'success' => 'yes'];
    }
}