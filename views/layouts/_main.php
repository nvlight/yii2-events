<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAssetEvents;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Category;
use app\models\Event;
use kartik\date\DatePicker;
use app\components\Debug;
use app\models\Type;
use yii\helpers\Url;

AppAssetEvents::register($this);

// используя это, делается отображение активного элемента меню в главном левом меню
$requestedRoute = Yii::$app->controller->module->requestedRoute;
$rr = explode('/',$requestedRoute); $rr1 = null;
//echo Debug::d($requestedRoute);
//echo Debug::d($rr0);
$rcontroller = "billing"; $raction = "index";
if (array_key_exists('0',$rr)) {
    $rcontroller = $rr[0];
}
if (array_key_exists('1',$rr)) {
    $raction = $rr[1];
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>

<?php
    //echo Debug::d(Yii::$app->db);
?>

    <nav class="navbar navbar-default visible-sm visible-xs">
        <div class="container-fluid">
            <!-- Brand и toggle сгруппированы для лучшего отображения на мобильных дисплеях -->
            <div class="navbar-header p015">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=Html::encode(Url::to([\app\components\AuthLib::AUTHED_PATH]))?>">
                    <i class="fa fa-sun-o" aria-hidden="true"></i>
                    <span>Events</span>
                </a>
            </div>

            <!-- Соберите навигационные ссылки, формы, и другой контент для переключения -->
            <?php
            $st = [
                'fa-file-text-o' => [
                    'index',
                    'Счет',
                    'billing'
                ],
                'fa-bolt' => [
                    'history',
                    'История',
                    'event',
                ],
                'fa-calendar' => [
                    'plan',
                    'Планирование',
                    'event',
                ],
                'fa-archive' => [
                    'index',
                    'Запись',
                    'post',
                ],
                'fa-video-camera' => [
                    'index',
                    'Кино',
                    'video',
                ],
                'fa-file' => [
                    'load',
                    'Документы',
                    'doc',
                ],
            ];
            ?>

            <div class="collapse navbar-collapse " id="bs-example-navbar-collapse-1">
                <ul class="list-unstyled mainul">
                    <?php foreach ($st as $stk => $stv): ?>
                        <?php if (1==1) : ?>
                            <li <?php if( ($rcontroller == $stv[2]) && ($raction == $stv[0])) echo 'class="active"' ?> >
                                <a href="<?=Url::to(["/{$stv[2]}/{$stv[0]}"]) ?>">
                                    <span class="ipic">
                                        <i class="fa <?=$stk?>" aria-hidden="true"></i>
                                    </span>
                                    <span class="text">
                                        <?=$stv[1]?>
                                    </span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

<noscript>
    <ul class="list-unstyled mainul hidden-md hidden-lg">
        <?php foreach ($st as $stk => $stv): ?>
            <?php if (1==1) : ?>
                <li <?php if( ($rcontroller == $stv[2]) && ($raction == $stv[0])) echo 'class="active"' ?> >
                    <a href="<?=Url::to(["/{$stv[2]}/{$stv[0]}"]) ?>">
                                        <span class="ipic">
                                            <i class="fa <?=$stk?>" aria-hidden="true"></i>
                                        </span>
                        <span class="text">
                                            <?=$stv[1]?>
                                        </span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</noscript>

<div class="wrapper">
    <div class="container1">
        <div class="row mp0">
            <div class="col-md-2 mp0 hidden-sm hidden-xs">
                <div class="leftbar">
                    <div class="caption">
                        <a href="<?=Url::to([\app\components\AuthLib::AUTHED_PATH])?>">
                            <i class="fa fa-pie-chart" aria-hidden="true"></i>
                            <span>Events</span>
                        </a>
                    </div>
                    <div class="main-menu">
                        <ul class="list-unstyled mainul">
                            <?php foreach ($st as $stk => $stv): ?>
                                <?php if (1==1) : ?>
                                    <li <?php if( ($rcontroller == $stv[2]) && ($raction == $stv[0])) echo 'class="active"' ?> >
                                        <a href="<?=Url::to(["/{$stv[2]}/{$stv[0]}"]) ?>">
                                            <span class="ipic">
                                                <i class="fa <?=$stk?>" aria-hidden="true"></i>
                                            </span>
                                            <span class="text">
                                                <?=$stv[1]?>
                                            </span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                        <?php //echo Debug::d($st,'st'); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-10 mp0">
                <div class="contentbar">
                    <?php
                        //echo \app\components\Debug::d(Yii::$app->controller->module->requestedRoute,'Yii::$app->controller->module->requestedRoute');
                        //echo \app\components\Debug::d($_SESSION['user'],'session...');
                        //echo sha1(Yii::$app->params['my_salt'].$_SESSION['user']['upass']);
                    ?>
                    <div class="user-line clearfix">
                        <div class="curr-date pull-left">
								<span>
									<?= date('d.m.Y')?>
								</span>
                        </div>
                        <div class="user-info pull-right">
                            <div class="dropdown">
                                <a href="<?=Url::to(['user/account'], true);?>"
                                   class="btn btn-default dropdown-toggle gg-dropdown" type="button" id="dropdownMenu1"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Здравствуйте,
                                    <strong>
                                    <?php
                                        $uname = '[UNDEFINED]';
                                        if (isset($_SESSION['user']['uname']) && !empty($_SESSION['user']['uname']) ){
                                            $uname = $_SESSION['user']['uname'];
                                        }
                                        echo Html::encode($uname);
                                    ?>
                                    </strong>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href="<?= Url::to(['/user/change-user-info'])?>"><i class="fa fa-pencil-square-o"></i> Редактирование</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?= Url::to([\app\components\AuthLib::LOG_OUT_PATH])?>"><i class="fa fa-power-off icon"></i> Выйти</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="content-line">
                        <div class="content">
                            <?= $content ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="copy">
		<span>
			&copy; Martin German. All rights reserved
		</span>
    </div>
</div>

<div id="go_to_top" style="display: none;"><i class="fa fa-chevron-up"></i></div>

<?php
$js1 = <<<JS

    $('#dropdownMenu1').on('click', function (e) {
        //e.preventDefault();
        //return false;
    });

// $(window).scroll(function () {
//     if ($(this).scrollTop() > 0) {
//         $('#go_to_top').fadeIn();
//     } else {
//         $('#go_to_top').fadeOut();
//     }
// });
// $('#go_to_top').click(function () {
//     $('body,html').animate({
//         scrollTop: 0
//     }, 400);
//     return false;
// });

$(window).scroll(function(){
if ($(this).scrollTop() > 200) {
$('#go_to_top').fadeIn();
} else {
$('#go_to_top').fadeOut();
}
});
 
$('#go_to_top').click(function(){
$("html, body").animate({ scrollTop: 0 }, 0);
return false;
});


JS;

$this->registerJs($js1);
?>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
