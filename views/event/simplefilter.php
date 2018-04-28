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

$this->registerCssFile('@web/css/bootstrap-select.min.css');

$this->title = 'Events | Простой фильтр';
$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page history'], 'keywords');

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
    echo Debug::d($_GET,'GET');
?>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-9">
                    <div class="table-cover">
                        <?php if ( isset($json) && is_array($json) && array_key_exists('rs',$json)
                            && is_array($json['rs']) && (count($json['rs'])) ) :
                        ?>
                        <div><span>Найдено строк: <?php echo count($json['rs']); ?></span></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover  gg-history">
                                <thead>
                                    <tr>
                                        <?php
                                        $abs_url = Url::toRoute($controller .'/' . $action, true);
                                        $buildHttpQuery = $json['buildHttpQuery'];
                                        $buildHttpQuery = $abs_url . '?' . $buildHttpQuery;
                                        $orderBy = $json['orderBy'];
                                        $sortType = $orderBy[array_keys($orderBy)[0]] === SORT_ASC ? SORT_DESC : SORT_ASC;
                                        ?>
                                        <?php //$buildHttpQuery = $controller . '/' . $action . '?' . $buildHttpQuery ?>
                                        <th><a href="<?=$buildHttpQuery."&sortColumn=id&sortType={$sortType}"?>">#</a></th>
                                        <th><a href="<?=$buildHttpQuery."&sortColumn=i_cat&sortType={$sortType}"?>">Категория</a></th>
                                        <th><a href="<?=$buildHttpQuery."&sortColumn=desc&sortType={$sortType}"?>">Описание</a></th>
                                        <th><a href="<?=$buildHttpQuery."&sortColumn=summ&sortType={$sortType}"?>">Сумма</a></th>
                                        <th><a href="<?=$buildHttpQuery."&sortColumn=dtr&sortType={$sortType}"?>">Дата</a></th>
                                        <th><a href="<?=$buildHttpQuery."&sortColumn=type&sortType={$sortType}"?>">Тип</a></th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($json['rs'] as $ek => $ev): ?>
                                        <tr class="actionId_<?=$ev->id?>">
                                            <td class="item_eid"><?=$ev->id?></td>
                                            <td class="item_cat"><?=$ev['category']->name?></td>
                                            <td class="item_desc"><?=$ev->desc?></td>
                                            <td class="item_summ"><?=$ev->summ?></td>
                                            <td class="item_dtr"><?=Yii::$app->formatter->asDate($ev->dtr);?></td>
                                            <td class="item_type">
                                                                            <span class="dg_type_style"
                                                                                  style="background-color: #<?=$ev['types']['color']?>;"
                                                                            >
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
                                                                                    <span class="glyphicon glyphicon-pencil">
                                                                            </span>
                                                                                </a>
                                                                                </span>
                                                <span class="btn-action" title="Удалить">
                                                                                <a class="evActionDelete"
                                                                                   data-id="<?=$ev->id?>" href="<?=Url::to(['event/del?id=' . $ev->id])?>"
                                                                                >
                                                                                    <span class="glyphicon glyphicon-trash">
                                                                                    </span>
                                                                                </a>
                                                                            </span>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    <?php if (array_key_exists('trs',$json)): ?>
                                        <?php if (count($json['trs'])): ?>
                                            <?php foreach($json['trs'] as $tk => $tv): ?>
                                                <tr>
                                                    <td colspan='3' class="tar">
                                                        <?=$tv[0]?>
                                                    </td>
                                                    <td><?=$tv[1]?></td>
                                                    <td colspan='3'>
                                                        <?=$tv[2]?> - <?=$tv[3]?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <?php
                            //                    echo Debug::d($json['evr'],'evr');
                            //                    echo Debug::d($json['dt_diff'],'dt_diff');
                            //                    echo Debug::d($json['trs'],'json_trs');
                            ?>
                        </div>

                        <?php
                        //echo 'link pager tut: ';
                        //echo Debug::d($json['pages']);
                        if (isset($json) && is_array($json) && array_key_exists('pages',$json)){
                            echo LinkPager::widget([
                                'pagination' => $json['pages'],
                            ]);
                        }
                        ?>
                        <?php else: ?>
                            <h3>Выберите параметры фильтра</h3>
                        <?php endif; ?>
                    </div>
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

<?php

$this->registerJsFile("@web/js/history.js",[
    'depends' => [
        //\yii\web\JqueryAsset::className()
        \yii\bootstrap\BootstrapPluginAsset::className()
    ]
]);

?>