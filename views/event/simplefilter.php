<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 11.03.2018
 * Time: 13:35
 */
use yii\widgets\ActiveForm;
use app\models\Category;
use app\models\Event;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\Type;
use yii\helpers\Html;
use app\components\Debug;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$this->registerCssFile('@web/css/bootstrap-select.min.css');

$this->title = 'Events | Простой фильтр';
$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page filter'], 'keywords');

//echo Debug::d(Yii::$app,'request');
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
//echo Url::to(['cont/act']);
//echo Html::a('in weight',[$controller .'/' . $action]);
//echo Debug::d($json,'json',2);

?>
<?php

    // for debug
    if ( isset($json) && is_array($json) && array_key_exists('rs',$json) && is_array($json['rs']) && (count($json['rs'])) ){
        //echo Debug::d($json['rs'][0],'trs');
    }
    //echo Debug::d($_SERVER,'server');
    //echo Debug::d($_GET,'GET');
?>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-9">
                    <div class="table-responsive">
                        <?php
                            if (array_key_exists('query',$json) && $json['query'] !== null){

                                $dataProvider = new ActiveDataProvider([
                                    //'query' => Event::find()->with('category')->with('types'),
                                    'query' => $json['query'],
                                    'pagination' => [
                                        'pageSize' => 20,
                                    ],
                                    'sort' => [
                                        'defaultOrder' => [
                                            'dtr' => SORT_DESC
                                        ]
                                    ]
                                ]);
                                echo GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'columns' => [
                                        [
                                            'class' => 'yii\grid\SerialColumn', // <-- тут
                                            // тут можно настроить дополнительные свойства
                                        ],
                                        [
                                            'label' => 'id',
                                            'attribute' => 'id',
                                        ],
                                        [
                                            'attribute' => 'i_cat',
                                            'format' => 'raw',
                                            'value' => function ($data) {
                                                $catname = $data->category->name;
                                                $countl = 15;
                                                (mb_strlen($catname) > $countl) ? $substrc = mb_substr($catname,0,$countl) . ' ...' : $substrc = $catname;
                                                return <<<DESC
        <span class="desc" >
            $substrc 
        </span>
DESC;
                                                return $substrc;
                                            },
                                            'contentOptions' =>['class' => 'table_class11','style'=>'white-space:nowrap;'],
                                        ],
                                        [
                                            'attribute' => 'desc',
                                            'format' => 'raw',
                                            'value' => function ($data) {
                                                $descf = $data->desc;
                                                $countl = 33;
                                                (mb_strlen($descf) > $countl) ? $substrc = mb_substr($descf,0,$countl) . ' ...' : $substrc = $descf;
                                                return <<<DESC
        <span class="desc" >
            $substrc 
        </span>
DESC;
                                            },
                                        ],
                                        [
                                            'attribute' => 'summ',
                                        ],
                                        [
                                            'attribute' => 'dtr',
                                            'contentOptions' =>['class' => 'table_class11','style'=>'white-space:nowrap;'],
                                        ],
                                        [
                                            //'class' => 'yii\grid\CheckboxColumn',
                                            'label' => 'Тип',
                                            'attribute' => 'type',
                                            'format' => 'raw',
                                            'value' => function ($data) {
                                                $typename = $data->types->name;
                                                $typecolor = $data->types->color;
                                                return <<<STR
        <span class="dg_type_style" style="background-color: #$typecolor; cursor: pointer;" >
            $typename
        </span>
STR;
                                            },
                                        ],
                                        [
                                            'class' => 'yii\grid\CheckboxColumn',
                                            // вы можете настроить дополнительные свойства здесь.
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            // вы можете настроить дополнительные свойства здесь.
                                            'template' => '{view} {update} {delete}',
                                        ],
                                    ],
                                ]);

                                //echo Debug::d($json['trs'],'trs');
                            }else{
                                ?>
                                    <div class="summary mb10">Результаты поиска</div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tr>
                                                <th>Ничего не найдено</th>
                                            </tr>
                                        </table>
                                    </div>
                                <?php
                            }
                        ?>

                    </div>

                    <?php if( isset($json) &&
                        is_array($json)
                        && array_key_exists('trs', $json) && is_array($json['trs'])
                        && count($json['trs']) ): ?>
                        <div class="summary">Подсчеты за период от <b><?=$json['trs'][0][2]?></b> до <b><?=$json['trs'][0][3]?></b>.</div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Тип</th>
                                    <th>Сумма</th>
                                </tr>
                                <?php foreach($json['trs'] as $k => $v):?>
                                    <tr>
                                        <td><?=$v[0]?></td>
                                        <td><?=$v[1]?></td>
                                    </tr>
                                <?php endforeach;?>
                            </table>
                        </div>
                    <?php endif; ?>

                </div>


                <div class="col-md-3">
                    <!-- Модальное окно - фильтр по дате, типам категорий и самим категориям -->
                    <div class="modal-dialog modal-resp" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modalSimpleFilterTitle">
                                    Фильтр
                                </h4>
                            </div>
                            <div class="modal-body">
                                <div class="inner">
                                    <?php
                                    $afParams = [
                                        'method'=>'get',
                                        'action' => ['/event/simple-filter'],
                                        'options' => [
                                            'class' => 'frmDoFilter',
                                        ]
                                    ];
                                    $form = ActiveForm::begin($afParams);
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

                                    <div class="modal-period mb10" >
                                        <?php
                                        // Event::find()->min('dtr');
                                        $dtr1 = (isset($json) && is_array($json) && array_key_exists('evr1',$json))
                                            ? \Yii::$app->formatter->asTime($json['evr1'], 'dd-MM-yyyy')
                                            : \Yii::$app->formatter->asTime(Event::find()->min('dtr'), 'dd-MM-yyyy');
                                        $dtr2 = (isset($json) && is_array($json) && array_key_exists('evr2',$json))
                                            ? \Yii::$app->formatter->asTime($json['evr2'], 'dd-MM-yyyy')
                                            : date('d-m-Y');
                                        echo '<label class="control-label">Выберите период</label>';
                                        echo DatePicker::widget([
                                            'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                            'name' => 'range1',
                                            'value' => $dtr1,
                                            'type' => DatePicker::TYPE_RANGE,
                                            'name2' => 'range2',
                                            'value2' => $dtr2,
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

                                    <?php
                                    $type_checked_all_css = '';
                                    //echo Debug::d($json,'json'); die;
                                    if (isset($json) && is_array($json)) {
                                        if ( array_key_exists('type_checked_all',$json) && $json['type_checked_all']) {
                                            $type_checked_all_css = "checked";
                                        }
                                    }
                                    $chechBoxexForTypeFilter = <<<CFCF
                                <div class="forSimpleFilter-ckeckAndUncheckAllTypes">
                                    <label>
                                        <input type="checkbox" name="type" $type_checked_all_css value="0">
                                        <i class="fa fa-square-o fa-2x"></i>
                                        <i class="fa fa-check-square-o fa-2x"></i>
                                        <span>Выбрать все типы событий</span>
                                    </label>
                                </div>
CFCF;
                                    ?>

                                    <?php

                                    //
                                    function is_inType($id,$type){
                                        $checked = '';
                                        $type = $GLOBALS[$type];
                                        if (in_array($id,$type)){
                                            $checked = 'checked';
                                        }
                                        return $checked;
                                    }

                                    // получение массива для 2-го параметра чекбоксЛиста
                                    $types = Type::find()->asArray()->all();
                                    // получение ключей типов, для постановки установленных чекбоксов
                                    $GLOBALS['ids_type'] = [];
                                    if (isset($json) && is_array($json) && array_key_exists('ids_type', $json)
                                        && is_array($json['ids_type']) && count($json['ids_type']) ){
                                        $ids_type = $json['ids_type'];
                                        $GLOBALS['ids_type'] = $ids_type;
                                        //echo Debug::d($ids_type,'$ids_type');
                                    }

                                    //
                                    ?>
                                    <div class="class-radioCheckBox">
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

                                    $naa = [];
                                    foreach($types as $ck => $cv){
                                        $naa[$cv['id']] = $cv['name'];
                                    }
                                    echo $form->field($eventMain,'type',[
                                        'template' => "<label for=''>Выберите тип</label>                                             
                                                     $chechBoxexForTypeFilter
                                                   <div>{input}</div>",
                                        'options' => ['class' => 'class-radioCheckBox']
                                    ])->checkboxList(
                                        $naa,
                                        [
                                            'item' => function($index, $label, $name, $checkbox, $value) {
                                                $ch = is_inType($value,'ids_type');
                                                $return = '<label>';
                                                $return .= '<input type="checkbox" name="' . $name . '" ' . $ch . ' value="' . $value . '"' . ' >'."\n";
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
                                    $cats_checked_all_css = '';
                                    if (isset($json) && is_array($json)) {
                                        if (array_key_exists('cats_checked_all',$json) &&  $json['cats_checked_all']) {
                                            $cats_checked_all_css = "checked";
                                        }
                                    }
                                    $chechBoxexForCatFilter = <<<CFCF
                                <div class="forSimpleFilter-ckeckAndUncheckAll">
                                    <label>                            
                                        <input type="checkbox" name="cat" $cats_checked_all_css value="0">
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
                                    // получение ключей типов, для постановки установленных чекбоксов
                                    $GLOBALS['ids_cats'] = [];
                                    if (isset($json) && is_array($json) && array_key_exists('ids_cats', $json)
                                        && is_array($json['ids_cats']) && count($json['ids_cats']) ){
                                        $ids_cats = $json['ids_cats'];
                                        $GLOBALS['ids_cats'] = $ids_cats;
                                    }

                                    $na = [];
                                    foreach($cats as $ck => $cv){
                                        $na[$cv['id']] = $cv['name'];
                                    }

                                    echo $form->field($eventMain,'i_cat',[
                                        'template' => "<label for=''>Выберите категории</label>
                                                    $chechBoxexForCatFilter
                                                <div>{input}</div>",
                                        'options' => ['class' => 'class-catsCheckBox']
                                    ])->checkboxList(
                                    //[1 => 'Доход', 2 => 'Расход'],
                                        $na,
                                        [
                                            'item' => function($index, $label, $name, $checked, $value) {
                                                $ch = is_inType($value,'ids_cats');
                                                $return = '<label>';
                                                $return .= '<input type="checkbox" name="' . $name . '" ' . $ch . ' value="' . $value . '"' . ' >'."\n";
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
                                    <?= Html::a('Сбросить', ['/event/simple-filter'], ['class'=>'btn btn-primary']) ?>
                                    <?= Html::submitButton( 'Применить', ['class' => 'btn btn-primary']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
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
                        $types2 = Type::find()->where(['i_user' => $_SESSION['user']['id']])->all();
                        $types30 = ArrayHelper::map($types2,'id','name');
                        $params21 = [
                            //'prompt' => 'Выберите категорию'
                            'id' => 'changeEventModal_typeId'
                        ];
                        $params = [
                            //'prompt' => 'Выберите категорию'
                            'id' => 'changeEventModal_catId'
                        ];
                        ?>
                        <?= $form->field($eventMain, 'i_cat')->dropDownList($cats3,$params)->label('Выберите категорию'); ?>
                        <?= $form->field($eventMain, 'type')->dropDownList($types30,$params21)->label('Выберите тип события'); ?>

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
                        // need ?! --- cats, $event
                        $catsMain = Category::find()->where(['i_user' => $_SESSION['user']['id']])->all();
                        $types2 = Type::find()->where(['i_user' => $_SESSION['user']['id']])->all();
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

$this->registerJsFile("@web/js/history.js",[
    'depends' => [
        //\yii\web\JqueryAsset::className()
        \yii\bootstrap\BootstrapPluginAsset::className()
    ]
]);

?>
