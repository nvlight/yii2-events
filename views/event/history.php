<?php
use yii\widgets\LinkPager;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use app\models\Category;
use app\models\Event;
use yii\helpers\ArrayHelper;
use app\components\Debug;
use app\models\Type;
use yii\helpers\Html;
use yii\helpers\Url;

$css1 = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css";
$js1 = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js";
$this->registerCssFile('@web/css/bootstrap-select.min.css');

$this->title = 'Events | История';
$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page history'], 'keywords');

$event = new Event();
$category = new Category();
$type = new Type();

?>

<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left" >Страница истории</h2>

        <div class="pull-right">
            <a href="<?=Url::to(['event/convert-to-xslx'])?>" class="convert2xlsx" title="экспорт всех записей в xlsx">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
            </a>
            <a href="<?=Url::to(['event/simple-filter'])?>" data-target="#modalSimpleFilter"
               class="reload noLink-doFilter" title="фильтр" data-toggle="modal"  >
                <i class="fa fa-filter"></i>
            </a>
            <a href="<?=Url::to(['event/create'])?>" data-target="#modalAddPost"
               class="reload noLink-addEvent" title="создание новой записи" data-toggle="modal"  >
                <i class="fa fa-gear icon"></i>
            </a>
        </div>

    </div>
    <div class="page-hr"></div>

    <?php if (Yii::$app->session->hasFlash('delEvent')) : ?>
        <h4 class="alert-success for-flash1" >
            <?= Yii::$app->session->getFlash('delEvent') ?>
        </h4>
    <?php endif; ?>

    <div class="page-content">

        <div class="row">
            <div class="col-md-12">
                <div class="caption-history clearfix">

                    <h4 class="pull-left">Список событий</h4>

                    <div class="pull-right clearfix ">
                        <div class="form-inline pull-right " style="">
                            <input class="form-control" id="searchColumn" placeholder="Сумма" type="text">
                        </div>
                        <select id="selectSearchColumn" class="selectpicker pull-right"  title="Параметр">
                            <option value="1">Категория</option>
                            <option value="2" selected>Сумма</option>
                            <option value="3">Дата</option>
                            <option value="4">Тип</option>
                            <option value="7">Описание</option>
                        </select>
                    </div>
                </div>

                <div class="table-cover">
                    <?php
                        $abs_url = Url::toRoute('event/history', true);
                        $rsort = '&sort='.$sort;
                    ?>
                    <div class="table-responsive">
                    <table class="table table-striped table-hover  gg-history">
                        <thead>
                        <tr>
                            <th><a href="<?=$abs_url.'?sortColumn=id'.$rsort?>">#</a></th>
                            <th><a href="<?=$abs_url.'?sortColumn=i_cat'.$rsort?>">Категория</a></th>
                            <th><a href="<?=$abs_url.'?sortColumn=desc'.$rsort?>">Описание</a></th>
                            <th><a href="<?=$abs_url.'?sortColumn=summ'.$rsort?>">Сумма</a></th>
                            <th><a href="<?=$abs_url.'?sortColumn=dtr'.$rsort?>">Дата</a></th>
                            <th><a href="<?=$abs_url.'?sortColumn=type'.$rsort?>">Тип</a></th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (count($events)) :
                                foreach ($events as $ek => $ev):
                            ?>
                                <tr class="actionId_<?=$ev->id?>">
                                    <td class="item_eid"><?=$ev->id?></td>
                                    <td class="item_cat"><?=$ev['category']->name?></td>
                                    <td class="item_desc"><?=$ev->desc?></td>
                                    <td class="item_summ"><?=$ev->summ?></td>
                                    <td class="item_dtr"><?=$ev->dtr?></td>
                                    <td class="item_type">
                                        <span class="dg_type_style" style="background-color: #<?=$ev['types']['color']?>;  " >
                                            <?=$ev->types->name?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="btn-action" title="Просмотр">
                                            <a class="evActionView"
                                               data-id="<?=$ev->id?>" href="<?=Url::to(['event/show?id=' . $ev->id])?>"
                                            >
                                                <span class="glyphicon glyphicon-eye-open" ></span>
                                            </a>
                                        </span>
                                        <span class="btn-action" title="Редактировать">
                                            <a class="evActionUpdate"
                                               data-id="<?=$ev->id?>" href="<?=Url::to(['event/upd?id=' . $ev->id])?>"
                                            >
                                                <span class="glyphicon glyphicon-pencil" >
                                                </span>
                                            </a>
                                        </span>
                                        <span class="btn-action" title="Удалить">
                                            <a class="evActionDelete"
                                               data-id="<?=$ev->id?>" href="<?=Url::to(['event/del?id=' . $ev->id])?>"
                                            >
                                                <span class="glyphicon glyphicon-trash" >
                                                </span>
                                            </a>
                                        </span>
                                    </td>
                                </tr>

                            <?php
                                endforeach;
                                endif;
                            ?>
                        </tbody>
                    </table>
                    </div>
                    <?php                     
                        echo LinkPager::widget([
                            'pagination' => $pages,
                    ]); ?>
                </div>

            </div>
        </div>

        <div class="page-hr">
            <hr>
        </div>

    </div>
</div>

<!-- Модальное окно - фильтр по дате, типам категорий и самим категориям -->
<div class="modal fade" id="modalSimpleFilter" tabindex="-1" role="dialog" aria-labelledby="modalSimpleFilter">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalSimpleFilterTitle">
                    Фильтр
                </h4>
            </div>
            <div class="modal-body">
                <div class="inner">
                    <?php $form = ActiveForm::begin([
                        'method'=>'post',
                        'action' => ['/site/action---SimpleFilter'],
                        'options' => [
                            'class' => 'frmDoFilter',
                            'id' => 'simpleFilter'
                        ]
                    ]); ?>

                    <div class="modal-period mb10">
                        <?php
                            echo '<label class="control-label">Выберите период</label>';
                            echo DatePicker::widget([
                                'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                'name' => 'range1',
                                'value' => Event::find()->min('dtr'),
                                'type' => DatePicker::TYPE_RANGE,
                                'name2' => 'range2',
                                'value2' => Yii::$app->formatter->asDate(date('Y-m-d')),
                                'language' => 'ru',
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'yyyy-mm-dd',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'class' => 'ch_zhiv1',
                                    'id' => 'mainfilter_dtrange1',

                                ],
                                'options2' => [
                                    'class' => 'ch_zhiv2',
                                    'id' => 'mainfilter_dtrange2',
                                ],
                            ]);
                        ?>
                    </div>

                    <?php
                    $chechBoxexForTypeFilter = <<<CFCF
                        <div class="forSimpleFilter-ckeckAndUncheckAllTypes">
                            <label>
                                <input type="checkbox" name="" value="">
                                <i class="fa fa-square-o fa-2x"></i>
                                <i class="fa fa-check-square-o fa-2x"></i>
                                <span>Выбрать все типы событий</span>
                            </label>
                        </div>
CFCF;
                    ?>

                    <div class="class-radioCheckBox_zerosumm">
                        <label for="">Искать строки с нулевой суммой</label>
                        <div class="class-search-for-zero-summ">
                            <label>
                                <input type="checkbox" name="zero_summ"
                                    <?php if (array_key_exists('zero_summ',$_GET)): ?>
                                        checked="checked"
                                    <?php endif; ?>
                                       value="0">
                                <i class="fa fa-square-o fa-2x"></i>
                                <i class="fa fa-check-square-o fa-2x"></i>
                                <span>Да</span>
                            </label>
                        </div>
                    </div>

                    <?php
                        // получение массива для 2-го параметра чекбоксЛиста
                        $types = Type::find()->where(['i_user' => $_SESSION['user']['id']])->asArray()->all();
                        $naa = [];
                        foreach($types as $ck => $cv){
                            $naa[$cv['id']] = $cv['name'];
                        }

                        echo $form->field($event,'type',[
                            'template' => "<label for=''>Выберите тип</label>                                             
                                             $chechBoxexForTypeFilter
                                           <div>{input}</div>",
                            'options' => ['class' => 'class-radioCheckBox']
                        ])->checkboxList(
                            //[1 => 'Доход', 2 => 'Расход'],
                            $naa,
                            [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $ch = '';
                                    $return = '<label>';
                                    $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '" tabindex="3"' . " {$ch} " . ' >'."\n";
                                    $return .= '<i class="fa fa-square-o fa-2x"></i>' ."\n" .
                                        '<i class="fa fa-check-square-o fa-2x"></i>' ."\n";
                                    $return .= '<span>' . ucwords($label) . '</span>' ."\n";
                                    $return .= '</label>';

                                    return $return;
                                },
                                'id' => 'simpleFilterModal_radioCheckBox'
                            ]
                        );
                    ?>
                    <?php
                        $chechBoxexForCatFilter = <<<CFCF
                        <div class="forSimpleFilter-ckeckAndUncheckAll">
                            <label>
                                <input type="checkbox" name="" value="">
                                <i class="fa fa-square-o fa-2x"></i>
                                <i class="fa fa-check-square-o fa-2x"></i>
                                <span>Выбрать все категории</span>
                            </label>
                        </div>
CFCF;
?>
                    <?php
                    // получение массива для первого параметра чекбоксЛиста
                    $cats = Category::find()->where(['i_user' => 1])->asArray()->all();
                    $na = [];
                    foreach($cats as $ck => $cv){
                        $na[$cv['id']] = $cv['name'];
                    }
                    echo $form->field($event,'i_cat',[
                        'template' => "<label for=''>Выберите категории</label>
                                            $chechBoxexForCatFilter
                                        <div>{input}</div>",
                        'options' => ['class' => 'class-catsCheckBox']
                    ])->checkboxList(
                        //[1 => 'Доход', 2 => 'Расход'],
                        $na,
                        [
                            'item' => function($index, $label, $name, $checked, $value) {
                                $ch = '';
                                $return = '<label>';
                                $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '" tabindex="3"' . " {$ch} " . ' >'."\n";
                                $return .= '<i class="fa fa-square-o fa-2x"></i>' ."\n" .
                                    '<i class="fa fa-check-square-o fa-2x"></i>' ."\n";
                                $return .= '<span>' . ucwords($label) . '</span>' ."\n";
                                $return .= '</label>';

                                return $return;
                            },
                            'id' => 'simpleFilterModal_radioCheckBox2'
                        ]
                    );
                    ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary doFilter">Применить</button>
                </div>
                    <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<!-- модальное окно для добавления события -->
<div class="modal fade" id="modalAddPost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                                'id' => 'siteAddEvent'
                            ]
                        ]); ?>

                        <?php

                        echo $form->field($event, 'i_cat')->dropDownList(
                            Category::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
                            ['id' => 'addEvent_catsId', 'prompt' => 'Выберите категорию']
                        )->label('Категория');

                        echo $form->field($event, 'type')->dropDownList(
                            Type::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
                            ['id' => 'addEvent_typesId', 'prompt'=>'Выберите тип события' ]
                        )->label('Тип события');

                        ?>

                        <?php
                        echo $form->field($event, 'dtr')->widget(DatePicker::className(),[
                            'language' => 'ru',
                            'name' => 'check_issue_date',
                            'value' =>  Yii::$app->formatter->asDate(date('Y-m-d')),
                            'options' => ['placeholder' => 'выберите дату', 'id' => 'addEventModal_datePicker'],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'todayHighlight' => true,
                                'format' => 'yyyy-mm-dd',
                            ]
                        ]);
                        ?>

                        <?= $form->field($event, 'summ')->label('Введите сумму') ?>
                        <?= $form->field($event, 'desc')->label('Введите описание') ?>

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

<!-- модальное окно для правки и показа (2 ин 1) события -->
<div class="modal fade" id="modalEventEdit" tabindex="-1" role="dialog" aria-labelledby="modalEventEditLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalEventEditTitle">
                    Редактирование события
                </h4>
            </div>
            <div class="modal-body">
                <section class="changeEventModal">
                    <div class="inner">

                        <?php $form = ActiveForm::begin([
                            'method'=>'post',
                            'action' => ['/site/change-event'],
                            'options' => [
                                'class' => 'changeEvent',
                            ]
                        ]); ?>

                        <input type="hidden" value="" id="evid">

                        <?php
                            echo $form->field($event, 'i_cat')->dropDownList(
                                Category::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
                                ['id' => 'changeEventModal_catId',]
                            )->label('Категория');

                            echo $form->field($event, 'type')->dropDownList(
                                Type::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
                                ['id' => 'changeEventModal_typeId',]
                            )->label('Тип события');

                            echo $form->field($event, 'dtr', ['options' => ['class' => 'changeEventModal_date']])
                                ->widget(DatePicker::className(),[
                                    'language' => 'ru',
                                    'name' => 'dp_3',
                                    'type' => 2,
                                    'value' =>  date('Y-m-d'),
                                    'options' => ['placeholder' => 'выберите дату', 'id' => 'changeEventModal_datePicker'],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy',
                                    ]
                                ]
                            );

                        ?>

                        <?= $form->field($event, 'summ')->label('Введите сумму') ?>
                        <?= $form->field($event, 'desc')->label('Введите описание') ?>

                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                            <button type="button" class="btn btn-primary btn-gg2 changeSubmitButton"
                                    data-dismiss="modal" >
                                Изменить
                            </button>
                            <button type="button" class="btn btn-primary btn-gg2 changeOkButton"
                                    data-dismiss="modal" >
                                Ок
                            </button>
                            <?php //echo Html::button('Изменить', ['class' => '']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php

$this->registerJsFile("@web/js/history.js",[
        'depends' => [
            //\yii\web\JqueryAsset::className()
            \yii\bootstrap\BootstrapPluginAsset::className()
         ]
    ]);
?>

