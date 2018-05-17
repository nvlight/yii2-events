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

class GraphicController extends Controller
{
    //
    public function actionIndex(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        //$q = Category::find()->select(['name'])->where(['>','id',1])->with('event')->all();
        $q = (new Query)
            ->select(['category.name'
                ," @p11 := (select abs(SUM(event.summ)) from event WHERE event.i_cat = category.id and event.type = 1) as 'p11'"
                ," @p12 := (select abs(SUM(event.summ)) from event WHERE event.i_cat = category.id and event.type = 2) as 'p12'"
                ," @p1 := (abs(@p11 - @p12) + 0) as 'p1' "
                , "category.`limit` as 'p2'"
            ])
            ->from('category')->where(['i_user' => $_SESSION['user']['id']])
            ->all();
        //echo Debug::d($q);

        // большая часть, написанная выше, сделано напросно, т.к. мы будем считать только расходы, а не их разность
        // получим здесь $remains - $all_rashod...
        $remains = $_SESSION['user']['remains'];
        $all_rashod = 0;
        foreach ($q as $qk => $qv) { $all_rashod += $qv['p12']; } //echo $qv['p12'] . "</br>"; }
        $diff_main = $remains - $all_rashod;

        // получили тут массив из: разность расходов с доходами и остаток
        $catPlans = $q;

        $this->layout = '_main';
        return $this->render('index',compact('catPlans','remains','diff_main'));
    }

}