<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 17.05.2018
 * Time: 16:17
 */

namespace app\controllers;

use yii\web\Controller;
use app\components\AuthLib;
use yii\db\Query;
use app\components\Debug;
use app\models\Graphic;
use Yii;

class GraphicController extends Controller
{
    //
    public function actionIndex(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $curr_year = Yii::$app->request->get('year') ? Yii::$app->request->get('year') : date('Y');
        //echo 'curr_year: '. $curr_year;

        $remains = $_SESSION['user']['remains'];

        $gr = new Graphic();
        // получаем все года, в которых есть данные.
        $years = $gr->getYears();

        //$year = $curr_year;
        //if (array_key_exists('year',$_POST) && in_array($_POST['year'],$years) ){
        //    $year = intval($_POST['year']);
        //}
        //echo Debug::d($year,'year'); die;

        // если запрашиваемый год не числится у нас, то выдаем текущий год, иначе, выдаем требуемый год (данные года)
        if ( !in_array($curr_year, $years) ){
            $year = date('Y');
        }else{
            $year = $curr_year;
        }

        list($ob_rs, $q_get_years_with_months) = $gr->getQueryRs($year);

        $pie_data = $gr->getPieData($ob_rs);

        $series = $gr->getSvodData($q_get_years_with_months,$years);
        //echo Debug::d($series,'series');

        $this->layout = '_main';
        return $this->render('index',
            compact('years','remains','series','pie_data','year'));
    }

    public function actionTest(){

        // get current year...
        echo date('Y');

    }

}