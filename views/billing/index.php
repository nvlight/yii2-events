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
        <?php
        if (Yii::$app->session->hasFlash('logined')):
            ?>
            <h4 class="alert-success p10" ><?=Html::encode(Yii::$app->session->getFlash('logined'))?></h4>
        <?php
        endif;
        ?>
        <?php
        if (Yii::$app->session->hasFlash('registrated')):
            ?>
            <h4 class="alert-success p10" >
                <?=Html::encode(Yii::$app->session->getFlash('registrated'))?>
            </h4>
        <?php
        endif;
        if (Yii::$app->session->hasFlash('updateRemains')):
        ?>
        <h4 class="alert-success p10" >
            <?=Html::encode(Yii::$app->session->getFlash('updateRemains'))?>
        </h4>
        <?php
        endif;
        ?>

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
                <div class="col-md-4">

                    <?php
                    $afParams = [
                        'method'=>'get',
                        'options' => [
                            'class' => 'frmChngRemains',
                        ]
                    ];
                    //$form = ActiveForm::begin($afParams);
                    ?>
                    <div class="input-group mb10">
                        <form class="form-inline">
                            <div class="form-group mb10">
                                <label for="basic-addon10">Общий лимит</label>
                                <input type="text" name="remains" class="form-control user_limit" id="basic-addon10"
                                       placeholder="Email" value="<?=html::encode($remains)?>">
                            </div>
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
                        </form>

                    </div>
                    <?php //ActiveForm::end(); ?>
                </div>
                <div class="col-md-4">
                    <p class="log">

                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <table class="table gg-billing">
                        <!-- <caption>
                            Счет
                        </caption> -->
                        <thead>
                        <tr">
                        <td colspan="2">Счет</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="active">
                            <td><i class="fa fa-rub" aria-hidden="true"></i> </td>
                            <td class="new_user_limit"><?=html::encode($remains)?></td>
                        </tr>
                        <tr class="active">
                            <td><i class="fa fa-eur" aria-hidden="true"></i></td>
                            <td class="billing_euro_schet"><?=html::encode(round($remains/Yii::$app->params['euro'],2,PHP_ROUND_HALF_DOWN))?></td>
                        </tr>
                        <tr class="active">
                            <td><i class="fa fa-usd" aria-hidden="true"></i> </td>
                            <td class="billing_dollar_schet"><?=html::encode(round($remains/Yii::$app->params['dollar'],2,PHP_ROUND_HALF_DOWN))?></td>
                        </tr>
                        </tbody>

                    </table>
                </div>
                <div class="col-md-8">
                    <table class="table gg-course">
                        <thead>
                        <tr>
                            <td>Курс</td>
                        </tr>
                        <tr>
                            <th>Валюта</th>
                            <th>Курс</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>rub</td>
                            <td>1</td>
                            <td><?= date('d.m.Y')?></td>
                        </tr>
                        <tr>
                            <td>Eur</td>
                            <td class="billing_euro_kurs"><?=html::encode(round(1/Yii::$app->params['euro'],4,PHP_ROUND_HALF_DOWN))?></td>
                            <td><?= date('d.m.Y')?></td>
                        </tr>
                        <tr>
                            <td>Usd</td>
                            <td class="billing_dollar_kurs"><?=html::encode(round(1/Yii::$app->params['dollar'],4,PHP_ROUND_HALF_DOWN))?></td>
                            <td><?= date('d.m.Y')?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php

$js1 = <<<JS

/* */
// $('#basic-focusout').on('click', function(e) {
//     $('input.user_limit').focusout();
// });

/* */
// $('input.user_limit222').on('focus', function(ee) {
//   $(this).keyup(function(e){
//     if (e.which == 13) {
//         ee.preventDefault();
//         updateUserLimit();
//         $(this).focusout();
//         return false;
//     } 
//     return false;
//   }); 
// });

/* */
$('.user_limit').keydown(function (event) {
    var key = event.keyCode || event.which;

    if (key === 13) {
        updateUserLimit();
        return false;
    }
});

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
            $('.billing_euro_schet').text(rs['k'][0]);
            $('.billing_dollar_schet').text(rs['k'][1]);
            $('.billing_euro_kurs').text(rs['k'][2]);
            $('.billing_dollar_kurs').text(rs['k'][3]); 
            $('.new_user_limit').text(rs['k'][4]);
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