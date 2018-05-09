<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 13.04.2018
 * Time: 18:08
 */

use app\components\Debug;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Events | Кино | Поиск';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events,Page videos - watch video'], 'keywords');
?>

<style>
    table {
        overflow:hidden;
        border:1px solid #d3d3d3;
        background:#fefefe;
        width:70%;
        margin:5% auto 0;
        -moz-border-radius:5px; /* FF1+ */
        -webkit-border-radius:5px; /* Saf3-4 */
        border-radius:5px;
        -moz-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
        -webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
    }

    th, td {
        padding:18px 28px 18px;
        text-align:center;
    }

    th {
        padding-top:22px;
        text-shadow: 1px 1px 1px #fff;
        background:#e8eaeb;
    }

    td {
        border-top:1px solid #e0e0e0;
        border-right:1px solid #e0e0e0;
    }

    tr.odd-row td {
        background:#f6f6f6;
    }

    td.first, th.first {
        text-align:left
    }

    td.last {
        border-right:none;
    }

    td {
        background: -moz-linear-gradient(100% 25% 90deg, #fefefe, #f9f9f9);
        background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f9f9f9), to(#fefefe));
    }

    tr.odd-row td {
        background: -moz-linear-gradient(100% 25% 90deg, #f6f6f6, #f1f1f1);
        background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f1f1f1), to(#f6f6f6));
    }

    th {
        background: -moz-linear-gradient(100% 20% 90deg, #e8eaeb, #ededed);
        background: -webkit-gradient(linear, 0% 0%, 0% 20%, from(#ededed), to(#e8eaeb));
    }

    tr:first-child th.first {
        -moz-border-radius-topleft:5px;
        -webkit-border-top-left-radius:5px; /* Saf3-4 */
    }

    tr:first-child th.last {
        -moz-border-radius-topright:5px;
        -webkit-border-top-right-radius:5px; /* Saf3-4 */
    }

    tr:last-child td.first {
        -moz-border-radius-bottomleft:5px;
        -webkit-border-bottom-left-radius:5px; /* Saf3-4 */
    }

    tr:last-child td.last {
        -moz-border-radius-bottomright:5px;
        -webkit-border-bottom-right-radius:5px; /* Saf3-4 */
    }
</style>

<div class="ytsearch1">

    <div class="row">
        <div class="col-md-12">
            <?php
                echo Debug::d($_REQUEST,'request',2);
                //echo Debug::d($rs,'record set');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <form action="<?=Url::to(['video/yt-search1'],true)?>" method="POST">

                <div class="form-group">
                    <label for="videoQueryString"><?=$q['caption']?></label>
                    <input type="text" name="yt-search-text" class="form-control"
                           id="videoQueryString" placeholder="Enter search string"
                           value="<?php echo Html::encode($q['value']); ?>">
                </div>
                <div class="form-group">
                    <label for="maxResults"><?=$maxResults['caption']?></label>
                    <input type="text" name="maxResults" class="form-control"
                           id="maxResults" placeholder="Enter results count from [0-50]"
                           value="<?php echo Html::encode($maxResults['value']); ?>">
                </div>
                <div class="form-group">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken()?>" />
                </div>
                <div class="form-group">
                    <label for="videoOrder"><?=$order['caption']?></label>
                    <select class="form-control" id="videoOrder" name="order">
                        <?php
                            foreach($orderArray as $k => $v){
                                ?>
                                    <option value="<?=Html::encode($k)?>" <?php if ($k === $order['key']): ?> selected <?php endif; ?> >
                                        <?=Html::encode($v)?>
                                    </option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="videoDuration"><?=$duration['caption']?></label>
                    <select class="form-control" id="videoDuration" name="duration">
                        <?php
                        foreach($durationArray as $k => $v){
                            ?>
                            <option value="<?=Html::encode($k)?>" <?php if ($k === $duration['key']): ?> selected <?php endif; ?> >
                                <?=Html::encode($v)?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
<!--                <div class="form-group">-->
<!--                    <label for="videoType">--><?//=$type['caption']?><!--</label>-->
<!--                    <select class="form-control" id="videoType" name="type">-->
<!--                        --><?php
//                        foreach($typeArray as $k => $v){
//                            ?>
<!--                            <option value="--><?//=Html::encode($k)?><!--" --><?php //if ($k === $type['key']): ?><!-- selected --><?php //endif; ?><!-- >-->
<!--                                --><?//=Html::encode($v)?>
<!--                            </option>-->
<!--                            --><?php
//                        }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
                <div class="form-group">
                    <label for="id_publishedAfter">publishedAfter</label>
                    <?php
                    echo \kartik\date\DatePicker::widget([
                        'language' => 'ru',
                        'name' => 'publishedAfter',
                        "value" =>  $publishedAfter,
                        'options' => ['placeholder' => 'выберите дату', 'id' => 'id_publishedAfter'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'todayHighlight' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]);
                    ?>
                </div>
                <div class="form-group">
                    <label for="id_publishedBefore">publishedBefore</label>
                        <?php
                            echo \kartik\date\DatePicker::widget([
                                'language' => 'ru',
                                'name' => 'publishedBefore',
                                "value" =>  $publishedBefore,
                                'options' => ['placeholder' => 'выберите дату', 'id' => 'id_publishedBefore'],
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'todayHighlight' => true,
                                    'format' => 'yyyy-mm-dd',
                                ]
                            ]);
                        ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('find it!', ['class' => 'btn btn-primary']) ?>
                </div>


            </form>
        </div>
    </div>


    <?php
        //echo Debug::d($part,'part',1,1);
        //echo Debug::d($rs,'rs',1,1);
        if (is_object($rs) && is_array($rs['modelData']) && is_array($rs['modelData']['pageInfo']) && array_key_exists('totalResults',$rs['modelData']['pageInfo'] ) && $rs['modelData']['pageInfo']['totalResults'] > 0 ){
            $items = $rs['modelData']['items'];
            //echo count($items);
            //echo Debug::d($items,'$items',1); die;
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>watch now!</th>
                                <th>title</th>
                                <th>description</th>
                                <th>channelTitle</th>
                                <th>PublishedAt</th>
                                <th>youtube link</th>
                            </tr>
                            <?php
                            foreach($items as $k => $v){
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        //echo Html::a('show', Url::to(['video/watch-yt','id' => $v['id']['videoId']],true), ['target' => '_blank'] );
                                        echo Html::a(Html::img(Url::to($v['snippet']['thumbnails']['default']['url'],true)), Url::to(['video/watch-yt','id' => $v['id']['videoId']],true),
                                            ['target' => '_blank', 'class' => 'loadVideoToModal', 'data-id' => $v['id']['videoId']] );

                                        //<a class="cp loadVideoToModal" data-id="19">
                                        //echo Html::img(Url::to($v['snippet']['thumbnails']['default']['url'],true));
                                        ?>
                                    </td>
                                    <td><?=$v['snippet']['title']?></td>
                                    <td><?=$v['snippet']['description']?></td>
                                    <td>
                                        <?=\yii\helpers\Html::a($v['snippet']['channelTitle'], Yii::$app->params['youytube_channelid_template'] . $v['snippet']['channelId'],['target' => '_blank',])?>
                                    </td>
                                    <td>
                                        <?=Yii::$app->formatter->asDatetime($v['snippet']['publishedAt'],'dd-MM-Y h:i:s')?>
                                    </td>
                                    <td>
                                        <?php echo Html::a('go2',
                                            Url::to("https://www.youtube.com/watch?v=" . $v['id']['videoId'],true),
                                            ['target' => '_blank'] )
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>

</div>

<!-- Модаль -->
<div class="modal fade" id="watchLideModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Просмотр видео</h4>
            </div>
            <div class="modal-body">

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
      url: '/video/get-yt-video-by-hash',
      type: 'GET',
      data: {id: id},
      success: function(res,status) {
        //console.log('status: '+status);
        var rs = $.parseJSON(res);
        if (rs['success'] === 'yes'){
            //console.log('limit change is success & reload is completed');  
            $('.modal-body').html(rs['iframe']);
            $('#watchLideModal').modal('show');
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
$('#watchLideModal').on('hidden.bs.modal', function () {
    //console.log('do something…'); 
    $('.modal-body').html('');
});



JS;


$this->registerJs($js1);
?>