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
use yii\bootstrap\Tabs;

$this->title = 'Events | Кино';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events,Page videos - show videos'], 'keywords');
?>

<div class="page-content">

    <div class="row">
        <?php echo Tabs::widget([
            'items' => [
                [
                    'label'     => 'Мои видео',
                    'url' => \yii\helpers\Url::to(['video/showvideos'],true),
                    'active'    =>  true
                ],
                [
                    'label'     => 'Добавить видео',
                    'url' => \yii\helpers\Url::to(['video/add-video'],true),
                ],
                [
                    'label'     =>  'Поиск видео',
                    'url' => \yii\helpers\Url::to(['video/search'],true),
                ],
                [
                    'label'     =>  'Поиск на YouTube',
                    'url' => \yii\helpers\Url::to(['video/yt-search1'],true),
                ],
            ]
        ]); ?>
    </div>

</div>

<div class="row">
    <hr>

    <?php if (isset($videos) && is_array($videos)) : ?>
        <?php if (count($videos)): ?>
            <div id="mpup">
                <?php foreach ($videos as $k => $v): ?>
                    <div class="col-md-4">
                        <?php $imgs = json_decode($v->thumbnails); $img1 = $imgs[1];  ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="videoClass1">
                                    <a class="cp loadVideoToModal"  data-id="<?=$v->id?>" >
                                        <div class="yt_img"  alt="<?=$v->title?>"
                                             style="background-image: url(<?=\yii\helpers\Url::to('@web/youytube_imgs/' . $img1,true)?>) " >
                                            <span class="yt_duration"><?=$v->duration?></span>
                                            <span class="tocenter">
											    <i class="fa fa-youtube-play" aria-hidden="true"></i>
										    </span>
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
                                    <p class="" style="margin-bottom: 0; ">
                                        <span>Просмотров: <strong><?=$v->viewcount?></strong></span>
                                    </p>
                                    <p class="" style="margin-bottom: 0; ">
                                        <span>Опубликовано: <strong><?=mb_substr($v->dt_publish,0,10)?></strong></span>
                                    </p>
                                </div>
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

<!-- Модаль -->
<div class="modal fade" id="watchVideModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Просмотр видео</h4>
            </div>
            <div class="modal-body">
                <iframe width="560" height="315"
                    src="https://www.youtube.com/embed/"
                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                </iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<?php
// loadVideoToModal

$js1 = <<<JS

/* */
function getYtVideoById(id){
    $.ajax({
      url: '/video/get-yt-video',
      type: 'GET',
      data: {id: id},
      success: function(res,status) {
        //console.log('status: '+status);
        var rs = $.parseJSON(res);
        if (rs['success'] === 'yes'){
            //console.log('limit change is success & reload is completed');  
            $('.modal-body').html(rs['iframe']);
            $('#watchVideModal').modal('show');
        }        
      }
      ,error: function(res) {
        alert('we got error --- ' + res);
      }
      ,beforeSend: function(e) {
        //console.log('beforeSend');  
      }
      ,complete: function() {
        //console.log('complete');      
      }
    });
}

/* */
$('.loadVideoToModal').on('click', function() {
    var id = $(this).data('id');
    console.log('id: ' + id);
    getYtVideoById(id);
    return false; 
});

//
$("#watchVideModal").on("hidden.bs.modal", function () {
  $('.modal-body').html('');
});

JS;

$this->registerJs($js1);
?>