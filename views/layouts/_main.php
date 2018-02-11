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

<div class="wrapper">
    <div class="container1">
        <div class="row mp0">
            <div class="col-md-2 mp0">
                <div class="leftbar">
                    <div class="caption">
                        <a href="<?=\yii\helpers\Url::to([''])?>">
                            <i class="fa fa-pie-chart" aria-hidden="true"></i>
                            <span>Events</span>

                        </a>

                    </div>
                    <div class="main-menu">
                        <ul class="list-unstyled">
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
                                                'site',
                                            ],
                                    'fa-calendar' => [
                                                'plan',
                                                'Планирование',
                                                'site',
                                            ],
                                    'fa-archive' => [
                                                'index',
                                                'Запись',
                                                'post',
                                            ],
                                ];
                            ?>
                            <?php foreach ($st as $stk => $stv): ?>
                                <?php if (1==1) : ?>
                                    <li <?php if( ($rcontroller == $stv[2]) && ($raction == $stv[0])) echo 'class="active"' ?> >

                                        <a href="<?=\yii\helpers\Url::to(["/{$stv[2]}/{$stv[0]}"]) ?>">
                                            <i class="fa <?=$stk?>" aria-hidden="true"></i>
                                            <span><?=$stv[1]?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </ul>
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
                                <button class="btn btn-default dropdown-toggle gg-dropdown" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Здравствуйте,
                                    <?php
                                        $uname = '[UNDEFINED]';
                                        if (isset($_SESSION['user']['uname']) && !empty($_SESSION['user']['uname']) ){
                                            $uname = $_SESSION['user']['uname'];
                                        }
                                        echo Html::encode($uname);
                                    ?>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href="<?= \yii\helpers\Url::to(['/user/change-user-info'])?>"><i class="fa fa-pencil-square-o"></i> Редактирование</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?= \yii\helpers\Url::to([\app\components\AuthLib::LOG_OUT_PATH])?>"><i class="fa fa-power-off icon"></i> Выйти</a></li>
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

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
