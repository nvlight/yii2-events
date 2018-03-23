<?php

namespace app\controllers;

use yii\web\Controller;

class SiteController extends Controller{

    //
    public function actionErrorAndThatsAllFalks(){
        $exception = Yii::$app->errorHandler->exception;
        $statusCode = $exception->statusCode;
        $name = $exception->getName();
        $message = $exception->getMessage();
        $this->layout = false;
        return $this->render('@app/views/site/error', [
            'exception' => $exception,
            'statusCode' => $statusCode,
            'name' => $name,
            'message' => $message
        ]);
    }





}
