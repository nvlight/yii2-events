<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 21.05.2018
 * Time: 11:33
 */

namespace app\models;

use yii\db\Query;
use yii\base\Model;

class Graphic extends Model
{
    //
    public function getPieData($ob_rs){

        // обработка и сбор 1 графика с общими сводками в серию
        $pie_data = [];
        if (isset($ob_rs) && is_array($ob_rs) && count($ob_rs)){
            $i = 4;
            foreach ($ob_rs as $k => $v){
                $pie_data[] = [
                    'name' => $v['nm'],
                    'y' => intval($v['sm']),
                    'color' => '#' . $v['cl'],
                ];
                $i++;
            }
        }

        return $pie_data;
    }

    //
    public function getSvodData($q_get_years_with_months,$years){
        $months = [1,2,3,4,5,5,6,7,8,9,10,11,12];
        $tp_ids = [1,2,3,4]; $tp = ['','Доход','Расход','Долг','Вклад'];
        $na = [];
        foreach($years as $year){
            foreach($months as $mk => $mv){
                foreach($tp_ids as $tpk => $tpv){

                    foreach($q_get_years_with_months as $dk => $dv){
                        //
                        if ($year == $dv['dtr'] && ($mv == $dv['mnth']) && $tpv == $dv['tp'] ){

                            $na[$tpv]['name'] = $tp[$tpv];
                            $na[$tpv]['color'] = '#'. $dv['cl'];
                            $na[$tpv]['data'][$year][$dv['mnth']] = intval($dv['sm']);
                        }
                    }
                }
            }
        }
        // сортируем сам массив, а потом по ключам внутренний массив inner
        // после добавляем недостающие ключи и обнуляем их, готово!
        // еще раз сортируем $v['data']
        ksort($na);
        //echo Debug::d($na,'$na');
        foreach($na as $k => &$v){ foreach($v['data'] as $kk => &$vv) { ksort($vv); } }
        foreach($na as $k => &$v){
            foreach($v['data'] as $kk => &$vv) {

                foreach ($months as $mk => $mv) {
                    if (!array_key_exists($mv, $vv)) {
                        $vv[$mv] = 0;
                    }
                }
            }
        }
        foreach($na as $k => &$v){ foreach($v['data'] as $kk => &$vv) { ksort($vv); } }
        // почти конец, осталось теперь объеденить массивы с годами в 1 массив
        $nac = $na;
        foreach($nac as $k => &$v){
            $tmp = [];
            foreach($v['data'] as $kk => &$vv) {

                foreach ($vv as $kkk => $vvv) {
                    $tmp[] = $vvv;
                }
            }
            $v['data'] = $tmp;
        }
        //echo Debug::d(count($nac),'count($na)');
        //echo Debug::d($na,'$na');
        //echo Debug::d($nac,'$nac',1); //die;
        // наконец-то ! создаем массив $series;
        $series = [];
        foreach($nac as $k => &$v){
            $series[] = &$v;
            //echo Debug::d($v,$k. ' current');
        }//die;
        //echo Debug::d($series,'series');
        return $series;
    }

    //
    public function getQueryRs(){

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

        return [$ob_rs, $q_get_years_with_months, $years];
    }
}