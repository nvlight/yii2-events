<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 29.03.2018
 * Time: 17:22
 */

use lo\widgets\magnific\MagnificPopupAsset;
use lo\widgets\magnific\MagnificPopup;
use yii\helpers\Html;

$this->title = 'Events | Кино';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events,Page videos - show videos'], 'keywords');
?>

<div class="row">
    <hr>
    <?php
    $videos = [];
    $videos = $all;

    ?>
    <?php if (isset($videos) && is_array($videos)) : ?>
        <?php if (count($videos)): ?>
            <div id="mpup">
                <?php foreach ($videos as $k => $v): ?>
                    <div class="col-md-4">
                        <?php $imgs = json_decode($v->thumbnails); $img1 = $imgs[1];  ?>
                        <div class="row">
                            <div class="col-md-12">
                                    <?php
                                        // for magnific popup
                                        /*
                                        <a href="<?php //echo \yii\helpers\Url::to('@web/youytube_imgs/' . $imgs[2],true)?>">
                                            <div class="yt_img"  alt="<?php //echo $v->title?>"
                                                 style="background-image: url(<?php //echo \yii\helpers\Url::to('@web/youytube_imgs/' . $img1,true)?>) " >
                                                <span class="yt_duration"><?php //echo $v->duration?></span>
                                            </div>
                                        </a>
                                        //*/
                                    ?>

                                    <a href="#">
                                        <div class="yt_img"  alt="<?=$v->title?>"
                                             style="background-image: url(<?=\yii\helpers\Url::to('@web/youytube_imgs/' . $img1,true)?>) " >
                                            <span class="yt_duration"><?=$v->duration?></span>
                                        </div>
                                    </a>

                                    <p class="mt10"><?=$v->title?></p>
                                    <p style="margin-bottom: 0; ">
                                        <span class="">Категория: <strong><?=$v->categoryvideo['name']?></strong></span>
                                    </p>
                                    <p style="margin-bottom: 0; ">Канал:
                                        <strong>
                                            <?=\yii\helpers\Html::a($v->channeltitle, Yii::$app->params['youytube_channelid_template'] . $v->channelid,['target' => '_blank',])?>
                                        </strong>
                                    </p>
                                    <p class="" style="">
                                        <span>Просмотров: <strong><?=$v->viewcount?></strong></span>
                                    </p>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
            echo MagnificPopup::widget(
                [
                    'target' => '#mpup',
                    'options' => [
                        'delegate'=> 'doc_ai',
                        //'delegate'=> 'a',
                    ],
                    //'effect' => 'with-zoom' //for zoom effect
                ]
            );
            ?>
        <?php else: ?>
            <div class="col-md-4"><h4>У вас пока нет сохраненных видео</h4></div>
        <?php endif; ?>
    <?php endif; ?>
</div>
