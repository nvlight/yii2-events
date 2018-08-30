<?php

namespace app\modules\str\controllers;

use app\modules\str\models\Project;
use yii\web\Controller;

/**
 * Default controller for the `str` module
 */
class DefaultController2 extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $projects = Project::find()->all();

        return $this->render('index', compact('projects') );
    }
}
