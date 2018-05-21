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

class GraphicController extends Controller
{
    //
    public function actionIndex($curr_year=2018){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $remains = $_SESSION['user']['remains'];

        $gr = new Graphic();
        list($ob_rs, $q_get_years_with_months, $years) = $gr->getQueryRs();

        $pie_data = $gr->getPieData($ob_rs);
        $year = [$curr_year];
        if (array_key_exists('year',$_POST) && in_array($_POST['year'],$years) ){
            $year = [$_POST['year']];
        }
        //echo Debug::d($year,'year');
        $series = $gr->getSvodData($q_get_years_with_months,$year);

        $this->layout = '_main';
        return $this->render('index',
            compact('years','remains','series','pie_data','year'));
    }

}