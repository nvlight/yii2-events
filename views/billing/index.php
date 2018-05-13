<?php
//echo \app\components\Debug::d($_SESSION,'session');
use yii\helpers\Html;
use app\components\Debug;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

//$remains = "chich maring <br> <h1 style='font-size: 30px;' >cetka</h1>";
$this->title = 'Events | Биллинг';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page billing'], 'keywords');
?>
    <div class="bill-inset">
        <?php if (Yii::$app->session->hasFlash('logined')): ?>
            <h4 class="alert-success p10" ><?=Html::encode(Yii::$app->session->getFlash('logined'))?></h4>
        <?php endif; ?>
        <?php
        if (Yii::$app->session->hasFlash('registrated')):
            ?>
            <h4 class="alert-success p10" >
                <?=Html::encode(Yii::$app->session->getFlash('registrated'))?>
            </h4>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('updateRemains')): ?>
            <h4 class="alert-success p10" >
                <?=Html::encode(Yii::$app->session->getFlash('updateRemains'))?>
            </h4>
        <?php endif; ?>
        <?php
        if (Yii::$app->session->hasFlash('courses')): ?>
            <h4 class="alert-success p10" ><?=Html::encode(Yii::$app->session->getFlash('courses'))?></h4>
        <?php endif; ?>

        <div class="page-caption clearfix">
            <h2 class="pull-left" >Страница счета</h2>
<!--            <span class="reload pull-right">-->
<!--                <i class="fa fa-refresh" aria-hidden="true"></i>-->
<!--            </span>-->
        </div>
        <div class="page-hr">
            <hr>
        </div>
        <?php
        try {
            10/0;
        } catch (ErrorException $e) {
            //Yii::warning("Деление на ноль.");
        }
        //throw new NotFoundHttpException('Thats just hapenned!');
        //echo Debug::d(Yii::$app->session);
        //echo Debug::d(Yii::$app->session['user']);
        //echo Debug::d(Yii::$app->request->cookies);
        //        $userHost = Yii::$app->request->userHost;
        //        $userIP = Yii::$app->request->userIP;
        //        echo Debug::d($userHost . $userIP);
        ?>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">

                    <form class="form-inline mb10">
                        <div class="form-group">
                            <label for="new_remains">Общий лимит в рублях</label>
                            <input type="text" name="remains" class="form-control user_limit" id="new_remains" placeholder="remains"  value="<?=html::encode($remains)?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default" id="chRemains">
                        <span class="spinRefresh">
                            <i class="fa fa-refresh"></i>
                        </span>
                                <span class="spinPreload">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                                Изменить
                            </button>
                            <nosript class="hidden">
                                <?= Html::submitButton( '', ['class' => 'input-group-addon fa fa-refresh']) ?>
                            </nosript>
                        </div>
                    </form>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                        //echo Debug::d($courses,'$courses');
                    ?>
                    <h4>Timestamp: <?= Yii::$app->formatter->asDatetime($courses['rs']['Timestamp'],'Y-MM-dd H:i:s')?></h4>
                    <h5>Count: <?= count($courses['rs']['Valute'])?></h5>
                    <div class="mb10">
                        <?=Html::a('Обновить курсы валют',['billing/update-courses'],['class' => 'btn btn-success'])?>
                    </div>
                    <?php if ($courses['success'] === 'yes'): ?>
                        <table class="table gg-billing">
                            <thead>
                                <tr>
                                    <td>NumCode</td>
                                    <td>CharCode</td>
                                    <td>Name</td>
                                    <td>Value</td>
                                    <td>Previous</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($courses['rs']['Valute'] as $k => $v): ?>
                                    <tr>
                                        <td><?=$v['NumCode']?></td>
                                        <td><?=$v['CharCode']?></td>
                                        <td><?=$v['Name']?></td>
                                        <td><?=$v['Value']/$v['Nominal']?></td>
                                        <td><?=$v['Previous']/$v['Nominal']?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php ?>
                        <?php ?>
                        <?php ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php

$js1 = <<<JS


/* */
$('.user_limit').keydown(function (event) {
    var key = event.keyCode || event.which;

    if (key === 13) {
        updateUserLimit();
        return false;
    }
});

/* */
function updateUserLimit(){
    //console.log('change limit & reload page: starting...');
    $.ajax({
      url: '/billing/change-user-limit',
      type: 'GET',
      data: {val:$('.user_limit').val()},
      success: function(res,status) {
        //console.log('status: '+status);
        var rs = $.parseJSON(res);
        if (rs['success'] === 'yes'){
            //console.log('limit change is success & reload is completed');  
            // $('.billing_euro_schet').text(rs['k'][0]);
            // $('.billing_dollar_schet').text(rs['k'][1]);
            // $('.billing_euro_kurs').text(rs['k'][2]);
            // $('.billing_dollar_kurs').text(rs['k'][3]); 
            // $('.new_user_limit').text(rs['k'][4]);
            // $('input.user_limit').val(rs['k'][4]);
        }        
      }
      ,error: function(res) {
        alert('we got error --- ' + res);
      }
      ,beforeSend: function(e) {
        //console.log('beforeSend');
            var c = $('.spinPreload').toggle();
            var d = $('.spinRefresh').toggle();
        setTimeout(function () {             
        }, 500);        
      }
      ,complete: function() {
        //console.log('complete');
        setTimeout(function () {
            var c = $('.spinPreload').toggle();
            var d = $('.spinRefresh').toggle();
        }, 1000);             
      }
    });
}

$('#chRemains').on('click', function() {
    updateUserLimit(); 
    return false; 
});
JS;

$this->registerJs($js1);
?>