<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 29.12.2018
 * Time: 15:11
 */

namespace app\controllers;


use yii\web\Controller;

class ForaslanController extends Controller
{

    public function actionIndex()
    {

        return $this->render('index');
    }
}