<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 07.05.2018
 * Time: 10:20
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\Controller;

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
}