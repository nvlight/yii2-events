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
$rr0 = explode('/',$requestedRoute); $rr1 = null;
if (array_key_exists('1',$rr0)) {
    $rr1 = $rr0[1];
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
                        <a href="<?=\yii\helpers\Url::to(['/site/index'])?>">
                            <i class="fa fa-pie-chart" aria-hidden="true"></i>
                            <span>Events</span>

                        </a>

                    </div>
                    <div class="main-menu">
                        <ul class="list-unstyled">
                            <?php
                                $st = [
                                    'fa-file-text-o' => [
                                                'billing',
                                                'Счет',
                                            ],
                                    'fa-bolt' => [
                                                'history',
                                                'История',
                                            ],
                                    'fa-calendar' => [
                                                'plan',
                                                'Планирование',
                                            ],
                                    'fa-archive' => [
                                                'post',
                                                'Запись',
                                            ],
                                ];
                            ?>
                            <?php foreach ($st as $stk => $stv): ?>
                                <?php if ($rr1) : ?>
                                    <li <?php if($rr1 == $stv[0]) echo 'class="active"' ?> >

                                        <a href="<?=\yii\helpers\Url::to(["/site/{$stv[0]}"]) ?>">
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
                                    <li><a href="#" data-toggle="modal" data-target="#modalAddPost" ><i class="fa fa-gear icon"></i> Сделать запись</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?= \yii\helpers\Url::to(['/site/change-user-info'])?>"><i class="fa fa-pencil-square-o"></i> Редактирование</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?= \yii\helpers\Url::to(['/site/logout'])?>"><i class="fa fa-power-off icon"></i> Выйти</a></li>
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

<div class="modal fade" id="modalAddPost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    Добавление нового события
                </h4>
            </div>
            <div class="modal-body">

                <section class="addEventModal">
                    <div class="inner">

                        <?php $form = ActiveForm::begin([
                            'method'=>'post',
                            'action' => ['/site/add-event'],
                            'options' => [
                                'class' => 'addEvent',
                            ]
                        ]); ?>

                        <?php
                        // need ?! --- cats, $event
                        $catsMain = Category::find()->where(['i_user' => $_SESSION['user']['id']])->all();
                        $types2 = Type::find()->all();
                        //echo Debug::d($types); die;
                        $eventMain = new Event();

                        // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
                        $cats3 = ArrayHelper::map($catsMain,'id','name');
                        $types3 = ArrayHelper::map($types2,'id','name');
                        $params1 = [
                            //'prompt' => 'Выберите категорию'
                            'id' => 'dropDownId_3'
                        ];
                        $params2 = [
                            //'prompt' => 'Выберите категорию'
                            'id' => 'idDropDownTypes'
                        ];
                        ?>
                        <?= $form->field($eventMain, 'i_cat')->dropDownList($cats3,$params1)->label('Выберите категорию'); ?>
                        <?= $form->field($eventMain, 'type')->dropDownList($types3,$params2)->label('Выберите тип события'); ?>

                        <?php
//                            $form->field($eventMain,'type',[
//                            'template' => '<label for="">Выберите тип</label><div>{input}</div>',
//                            ])->radioList(
//                                [1 => 'Доход', 2 => 'Расход'],
//                                [
//                                    'item' => function($index, $label, $name, $checked, $value) {
//                                        $ch = '';
//                                        if ($index === 0) {
//                                            $ch = "checked=''";
//                                        }
//                                        $return = '<label>';
//                                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3"' . " {$ch} " . ' >'."\n";
//                                        $return .= '<i class="fa fa-circle-o fa-2x"></i>' ."\n" .
//                                            '<i class="fa fa-dot-circle-o fa-2x"></i>' ."\n";
//                                        $return .= '<span>' . ucwords($label) . '</span>' ."\n";
//                                        $return .= '</label><br/>';
//
//                                        return $return;
//                                    }
//                                ]
//                            );
                        ?>

                        <?php
                            echo $form->field($eventMain, 'dtr')->widget(DatePicker::className(),[
                                    'language' => 'ru',
                                    'name' => 'check_issue_date',
                                    "value" =>  '16-11-2017',
                                    'options' => ['placeholder' => 'выберите дату', 'id' => 'addEventModal_datePicker'],
                                    'pluginOptions' => [
                                            'autoclose'=>true,
                                            'todayHighlight' => true,
                                            'format' => 'dd-mm-yyyy',
                                        ]
                                ]);
                        ?>

                        <?= $form->field($eventMain, 'summ')->label('Введите сумму') ?>
                        <?= $form->field($eventMain, 'desc')->label('Введите описание') ?>

                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-gg2']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </section>

            </div>
        </div>
    </div>
</div>



<?php
$js3 = <<<JS
$('form.addEvent').on('beforeSubmit', function(e){
    //e.preventDefault();
    var data = $(this).serialize();
    //alert('add category...')
    //console.log('add event by modal form...');
    $.ajax({
        url: '/web/site/add-post-modal',
        type: 'POST',
        data: data,
        success: function(res){
            //console.log(res);
            var np = $.parseJSON(res);
            //alert(np['message']);
            $('#modalAddPost').modal('hide');
            if (np['success'] === 'yes'){
                $('form.addEvent').trigger( 'reset' );
            }
            $('table.gg-history').prepend(np['trh']);

        },
        error: function(res){
            console.log(res);
        }
    });

    return false;
});
JS;

$this->registerJs($js3);

?>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
