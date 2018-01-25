<?php
use yii\widgets\LinkPager;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use app\models\Category;
use app\models\Event;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;

$css1 = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css";
$js1 = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js";
//$this->registerCss($css1);
//$this->registerJs($js1);
$this->registerCssFile('@web/css/bootstrap-select.min.css');

$this->title = 'Events | История';
$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page history'], 'keywords');

?>

<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left" >Страница истории</h2>
        <h3>
            <?php
                //echo \app\components\Debug::d($_SERVER);
                //echo \app\components\Debug::d(Yii::$app->params['additional_url_part']);
            ?>
        </h3>

        <span id="gg-filter" class="reload pull-right" data-toggle="modal" data-target="#modalSimpleFilter">
                    <i class="fa fa-filter" aria-hidden="true"></i>
        </span>
        <div class="pull-right">
            <?php
            $dataProvider = new ActiveDataProvider([
                'query' => $ev2,
                'pagination' => [
                    'pageSize' => 4,
                ],
            ]);

            $gridColumns = [
                //['class' => 'yii\grid\SerialColumn'],
                'id',
                'category.name',
                'desc',
                'summ',
                'type',
                //['class' => 'yii\grid\ActionColumn'],
            ];

            // Renders a export dropdown menu
            //echo ExportMenu::widget([ 'dataProvider' => $dataProvider,  'columns' => $gridColumns ]);
            ?>
        </div>
    </div>
    <div class="page-hr">
        <hr>
    </div>
    <div class="page-content">

        <div class="row">
            <div class="col-md-12">
                <div class="caption-history clearfix">
                    <h4 class="pull-left">Список событий</h4>

                    <div class="pull-right clearfix ">
                        <div class="form-inline pull-left " style="margin-right: 10px;">
                            <input class="form-control" id="searchColumn" placeholder="Сумма" type="text">
                        </div>

                        <select id="selectSearchColumn" class="selectpicker pull-right"  title="Параметр">
                            <option value="1">Категория</option>
                            <option value="2" selected>Сумма</option>
                            <option value="3">Дата</option>
                            <option value="4">Тип</option>
                        </select>

                    </div>
                </div>
                <?php
                if ($events){
                    //echo \app\components\Debug::d($events,'events');
                }
                ?>
                <div class="table-cover">
                    <?php
                        //echo \app\components\Debug::d($_SERVER,'server');

                        $ruri = $_SERVER['REQUEST_URI'];
                        $strp = mb_strpos($ruri,'?');
                        if ($strp){
                            $ruri = mb_substr($ruri,0,$strp);
                            //echo 'ruri: '. $ruri;
                        }
                        $rsort = '&sort='.$sort;
                        // encode
                        $ruri = Html::encode($ruri);
                        $rsort = Html::encode($rsort);
                    ?>
                    <table class="table table-striped table-hover gg-history">
                        <thead>
                        <tr>
                            <th><a href="<?=$ruri.'?sortcol=id'.$rsort?>">#</a></th>
                            <th><a href="<?=$ruri.'?sortcol=i_cat'.$rsort?>">Категория</a></th>
                            <th><a href="<?=$ruri.'?sortcol=desc'.$rsort?>">Описание</a></th>
                            <th><a href="<?=$ruri.'?sortcol=summ'.$rsort?>">Сумма</a></th>
                            <th><a href="<?=$ruri.'?sortcol=dtr'.$rsort?>">Дата</a></th>
                            <th><a href="<?=$ruri.'?sortcol=type'.$rsort?>">Тип</a></th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($events as $ek => $ev):
                            switch ($ev->type){
                                case 1: $evtype[1] = ['success', 'доход']; $evtypeid = 1;  break;
                                case 2: $evtype[2] = ['danger',  'расход'];  $evtypeid = 2; break;
                                default:$evtype[3] = ['type_undefined', 'просто событие']; $evtypeid = 0;
                            }
                        ?>
                            <tr class="actionId_<?=$ev->id?>">
                                <td class="item_eid"><?=$ev->id?></td>
                                <td class="item_cat"><?=$ev['category']->name?></td>
                                <td class="item_desc"><?=$ev->desc?></td>
                                <td class="item_summ"><?=$ev->summ?></td>
                                <td class="item_dtr"><?= Yii::$app->formatter->asDate($ev->dtr);?></td>
                                <?php
//                                    echo DatePicker::widget([
//                                            'name' => 'check_issue_date',
//                                            'value' => date('d-m-Y'), //strtotime('+2 days')),
//                                            'options' => ['placeholder' => 'выберите дату'],
//                                            'pluginOptions' => [
//                                                'format' => 'dd-mm-yyyy',
//                                                'todayHighlight' => true
//                                            ]
//                                        ]
//                                    );
                                ?>
                                <td class="item_type"><span class="<?= $evtype[$evtypeid][0] ?>"><?= $evtype[$evtypeid][1] ?></span></td>
                                <td>
                                    <span class="btn-action" title="Просмотр">
                                        <a class="evActionView"
                                           <?php // echo " href="\yii\helpers\Url::to(['/site/show-event/?id='.$ev->id])" ';  ?>
                                           data-id="<?=$ev->id?>" href="#"
                                        >
                                            <span class="glyphicon glyphicon-eye-open" ></span>
                                        </a>
                                    </span>
                                    <span class="btn-action" title="Редактировать">
                                        <a class="evActionUpdate"
                                            <?php // echo "onclick=editEvent(".$ev->id".)"; return false; ";  ?>
                                            data-id="<?=$ev->id?>" href="#"
                                        >
                                            <span class="glyphicon glyphicon-pencil" >
                                            </span>
                                        </a>
                                    </span>
                                    <span class="btn-action" title="Удалить">
                                        <a class="evActionDelete"
                                           data-id="<?=$ev->id?>" href="#"
                                        >
                                            <span class="glyphicon glyphicon-trash" >
                                            </span>
                                        </a>
                                    </span>
                                </td>
                            </tr>

                        <?php
                            endforeach;
                        ?>
                        </tbody>
                    </table>
                    <?php
                        //echo \app\components\Debug::d($pages,'pages');
                        echo LinkPager::widget([
                        'pagination' => $pages,
                    ]); ?>
                </div>

            </div>
        </div>

        <div class="page-hr">
            <hr>
        </div>

        <div class="row">
            <div class="col-md-12 col-md-offset-0 ">
                <div id="donutchart" class="" style="width: 900px; height: 500px;"></div>
            </div>
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
                        ]
                    ]); ?>

                    <?php
                    // need ?! --- cats, $event
                    $catsMain = Category::find()->where(['i_user' => $_SESSION['user']['id']])->all();
                    //echo Debug::d($cats);
                    $eventMain = new Event();

                    // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
                    $cats3 = ArrayHelper::map($catsMain,'id','name');
                    $params = [
                        //'prompt' => 'Выберите категорию'
                        'id' => 'changeEventModal_catId'
                    ];
                    ?>

                    <div class="modal-period" style="width: 250px; margin-bottom: 10px;">
                        <?php
                            echo '<label class="control-label">Выберите период</label>';
                            echo DatePicker::widget([
                                'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                'name' => 'from_date',
                                'value' => date('d-m-Y'),
                                'type' => DatePicker::TYPE_RANGE,
                                'name2' => 'to_date',
                                'value2' => date('d-m-Y'),
                                'language' => 'ru',

                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'dd-mm-yyyy',
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

                    <?= $form->field($eventMain,'type',[
                            'template' => '<label for="">Выберите тип</label><div>{input}</div>',
                            'options' => ['class' => 'class-radioCheckBox']
                        ])->checkboxList(
                            [1 => 'Доход', 2 => 'Расход'],
                            [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $ch = '';
                                    if ($index === 0) {
                                        $ch = "checked=''";
                                    }
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
                    //echo \app\components\Debug::d($cats);
                    //echo \app\components\Debug::d($na);

                    echo $form->field($eventMain,'i_cat',[
                        'template' => "<label for=''>Выберите категории</label>$chechBoxexForCatFilter<div>{input}</div>",
                        'options' => ['class' => 'class-catsCheckBox']
                    ])->checkboxList(
                        //[1 => 'Доход', 2 => 'Расход'],
                        $na,
                        [
                            'item' => function($index, $label, $name, $checked, $value) {
                                $ch = '';
                                if ($index === 0) {
                                    $ch = "checked=''";
                                }
                                $return = '<label>';
                                $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '" tabindex="3"' . " {$ch} " . ' >'."\n";
                                $return .= '<i class="fa fa-square-o fa-2x"></i>' ."\n" .
                                    '<i class="fa fa-check-square-o fa-2x"></i>' ."\n";
                                $return .= '<span>' . ucwords($label) . '</span>' ."\n";
                                $return .= '</label><br/>';

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

<!-- модальное окно для правки и показа события -->
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

                        ?>
                        <?= $form->field($eventMain, 'i_cat')->dropDownList($cats3,$params)->label('Выберите категорию'); ?>

                        <?= $form->field($eventMain,'type',[
                            'template' => '<label for="">Выберите тип</label><div>{input}</div>',
                            'options' => ['class' => 'chichhch_field_options']
                        ])->radioList(
                            [1 => 'Доход', 2 => 'Расход'],
                            [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $ch = '';
                                    if ($index === 0) {
                                        $ch = "checked=''";
                                    }
                                    $return = '<label>';
                                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3"' . " {$ch} " . ' >'."\n";
                                    $return .= '<i class="fa fa-circle-o fa-2x"></i>' ."\n" .
                                        '<i class="fa fa-dot-circle-o fa-2x"></i>' ."\n";
                                    $return .= '<span>' . ucwords($label) . '</span>' ."\n";
                                    $return .= '</label><br/>';

                                    return $return;
                                },
                                'id' => 'changeEventModal_radioId'
                            ]
                        ); ?>

                        <?php
                            echo $form->field($eventMain, 'dtr', ['options' => ['class' => 'changeEventModal_date']])
                                ->widget(DatePicker::className(),[
                                    'language' => 'ru',
                                    'name' => 'dp_3',
                                    'type' => 2,
                                    "value" =>  '16-11-2017',
                                    'options' => ['placeholder' => 'выберите дату', 'id' => 'changeEventModal_datePicker'],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy',
                                    ]
                                ]
                            );

                        ?>

                        <?= $form->field($eventMain, 'summ')->label('Введите сумму') ?>
                        <?= $form->field($eventMain, 'desc')->label('Введите описание') ?>

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

