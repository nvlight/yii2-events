<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 29.03.2018
 * Time: 17:22
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Tabs;

$this->title = 'Events | Кино';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page videos'], 'keywords');
?>

<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left">Кино</h2>
    </div>

    <div class="page-hr"></div>

    <div class="page-content">

        <div class="row">
            <?php echo Tabs::widget([
                'items' => [
                    [
                        'label'     => 'Мои видео',
                        'content'   =>  $this->render('showvideos', ['model' => $model,'all' => $all]),
                        'active'    =>  true
                    ],
                    [
                        'label'     =>  'Добавить видео',
                        'content'   =>  $this->render('addvideo', ['model' => $model, ]),

                    ],
                    [
                        'label'     =>  'Поиск видео',
                        'url' => \yii\helpers\Url::to(['video/search'],true),
                    ],
                    [
                        'label'     =>  'Поиск видео 2',
                        'url' => \yii\helpers\Url::to(['video/search2'],true),
                    ],
                    [
                        'label'     =>  'Поиск видео YT 1',
                        'url' => \yii\helpers\Url::to(['video/yt-search1'],true),
                    ],
                ]
            ]); ?>
        </div>





    </div>

</div>