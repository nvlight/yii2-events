<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 08.04.2018
 * Time: 18:37
 */



$this->title = 'Events | Кино';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events,Page videos - watch video'], 'keywords');
?>

<div class="row">
    <?php if ($id): ?>
        <iframe
            width="560" height="315"
            src="https://www.youtube.com/embed/<?=$id?>"
            frameborder="0"
            allow="autoplay; encrypted-media"
            allowfullscreen
        >
        </iframe>
    <?php else: ?>
        <h3>Не удалось воспроизвести видео, пожалуйста, попробуйте позже</h3>
    <?php endif; ?>
</div>
