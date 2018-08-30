<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 27.05.2018
 * Time: 23:13
 */

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\Type;
use app\models\Category;
use app\models\Event;
use mihaildev\ckeditor\CKEditor;

$this->title = 'Events | История (new)';
$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page history (new)'], 'keywords');

$event = new Event();
$category = new Category();
$type = new Type();

?>


<div class="page-content">

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

    <div class="table-responsive">
        <?php

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
                        $countl = 20;
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
                        $countl = 61;
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
        ?>
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
                        <?php //echo $form->field($event, 'desc')->label('Введите описание') ?>
                        <?php echo $form->field($event, 'desc')->widget(CKEditor::className()) ?>

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