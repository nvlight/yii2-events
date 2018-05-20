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

class GraphicController extends Controller
{
    //
    public function actionIndex(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        // получим тут общую сумму по 4-м типам для текущего пользователя
        $q = (new Query)
            ->select([
                'event.type tp, sum(event.summ) sm, type.name nm, type.color cl'
            ])
            ->from('event')->where(['event.type' => [1,2,3,4], 'event.i_user' => $_SESSION['user']['id']])
            ->leftJoin('type','type.id=event.type')
            ->groupBy('tp')
            ->orderBy('tp')
            ->indexBy('tp')
            ->all();
        //echo Debug::d($q); die;
        $ob_rs = $q;
        $remains = $_SESSION['user']['remains'];

        $q_get_year_arrays = (new Query())
            ->select('DISTINCT year(dtr) year')
            ->from('event')
            ->orderBy('year')
            ->all();
        $years = [];
        if ($q_get_year_arrays){
            foreach($q_get_year_arrays as $k => $v){
                $years[] = $v['year'];
            }
        }
        //echo Debug::d($years,'$years');

        $q_get_years_with_months = (new Query())
            ->select('year(event.dtr) dtr,month(event.dtr) mnth, monthname(dtr) mnthnm, event.type tp, sum(event.summ) sm, type.name nm, type.color cl')
            ->from('event')
            ->where(['event.type' => [1,2,3,4]])
            ->leftJoin('type','type.id=event.type')
            ->groupBy('mnth,tp')
            ->orderBy('dtr,mnth,tp')
            ->all();
        //echo Debug::d($q_get_years_with_months,'$q_get_years_with_months');

        $this->layout = '_main';
        return $this->render('index',
            compact('ob_rs','years','remains','q_get_years_with_months'));
    }

}