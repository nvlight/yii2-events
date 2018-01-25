<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 14.01.2018
 * Time: 19:57
 */

namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

class HelloWidget extends Widget
{
    public $message;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = 'Hello World';
        }
    }

    public function run()
    {
        return Html::encode($this->message);
    }
}