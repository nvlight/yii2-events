<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 29.03.2018
 * Time: 17:22
 */

//use lo\widgets\magnific\MagnificPopupAsset;
use lo\widgets\magnific\MagnificPopup;

$this->title = 'Events | Кино';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events,Page videos - show videos'], 'keywords');
?>

<div class="row">
    <?php
    $videos = [];
    $videos = $all;

    ?>
    <?php if (isset($videos) && is_array($videos)) : ?>
        <?php if (count($videos)): ?>
            <?php foreach ($videos as $k => $v): ?>
                <div class="col-md-4">
                    <iframe class="uvideo"
                        width="100%" height="250"
                        src="https://www.youtube.com/embed/<?=$v->video_id?>"
                        frameborder="0" allow="autoplay; encrypted-media"
                        allowfullscreen>
                    </iframe>
                    <div class="row">
                        <div class="col-md-8"><p><?=$v->title?></p></div>
                        <div class="col-md-4 text-right">Категория: <strong><p><?=$v->categoryvideo['name']?></p></strong></div>
                        <div class="col-md-8"><p>Канал: <strong><?=$v->channeltitle?></strong></p></div>
                        <div class="col-md-8"><p>Просмотров: <strong><?=$v->viewcount?></strong></p></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-md-4"><h4>У вас пока нет сохраненных видео</h4></div>
        <?php endif; ?>
    <?php endif; ?>
</div>
