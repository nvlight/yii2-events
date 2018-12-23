<?php

use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use yii\web\JsExpression;
use app\components\Debug;

$this->title = 'Events | Графики и диаграммы';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events,Page graphik'], 'keywords');

HighchartsAsset::register($this)->withScripts(['highstock', 'modules/exporting', 'modules/drilldown']);

?>

<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left" >Страница графиков и диаграмм</h2>

    </div>
    <div class="page-hr"></div>
    <?php

    ?>
    <div class="page-content">

        <?php

        // общий график со сводной по типам
        echo Highcharts::widget([
            'scripts' => [
                'modules/exporting',
                'themes/grid-light',
            ],
            'options' => [
                'chart' => [
                    'plotBackgroundColor' => null,
                    'plotBorderWidth' => null,
                    'plotShadow' => false,
                    'type' => 'pie'
                ],
                'title' => [
                    'text' => 'Сумма ресурсов по типам'
                ],
                'tooltip' => [
                    'pointFormat' => '{series.name}: <b>{point.y}</b>'
                ],
                'plotOptions' => [
                    'pie' => [
                        'allowPointSelect' => true,
                        'cursor' => 'pointer',
                        'dataLabels' => [
                            'enabled' => true,
                            'format' => '<b>{point.name}</b>: {point.y}',
                            'style' => [
                                'color' => new JsExpression('Highcharts.theme && Highcharts.theme.contrastTextColor') || 'black',
                            ]
                        ]
                    ]
                ],
                'series' => [[
                    'name' => 'Сумма',
                    'colorByPoint' => true,
//                    'data' => [
//                        [
//                            'name' => 'Chrome',
//                            'y' => 61.41,
//                            'sliced' => true,
//                            'selected' => true
//                        ]
//                    ]
                    'data' => $pie_data,
                ]]
            ]
        ]);

        // покажем тут годы, чтобы можно было их выбирать

        $form = \yii\widgets\ActiveForm::begin([
            'method'=>'post',
            //'action' => ['/site/add-event'],
            'options' => [
                'class' => 'classGraphicYear',
                'id' => 'idGraphicYear'
            ]
        ]);
        ?>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <?php
                    //echo Debug::d($_POST,'$_POST');
                    //echo Debug::d($years,'$years');
                    $nw = []; foreach($years as $k => $v) { $nw[$v] = $v; }
                    echo Html::label('Выберите год','iyear',['class' => 'control-label']);
                    ?>
                    <div class="form-group">
                        <?php
                            echo Html::dropDownList('year',[],$nw,['class' => 'form-control',
                                'options' => [ $year => ['Selected' => true], ],
                                'prompt' => 'Выберите год',
                                'id' => 'iyear',
                            ]);
                        ?>
                    </div>
                </div>
                <?php
                    echo Html::submitButton('изменить',['class' => 'btn btn-primary']);
                ?>
            </div>
        </div>

        <?php
        \yii\widgets\ActiveForm::end();

        // график с месяцами, разделенными по типам
        echo Highcharts::widget([
            'scripts' => [
                'modules/exporting',
                'themes/grid-light',
            ],
            'options' => [
                'chart' => [
                    'type' => 'column'
                ],
                'title' => [
                    'text' => 'Комбинированный график хождения денежных потоков',
                ],
                'xAxis' => [
                    'categories' => ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь' , 'Октябрь', 'Ноябрь', 'Декабрь'],
                ],
                'plotOptions' => [
                    'column' => [
                        'dataLabels' => [
                            'enabled' => true
                        ],
                    ]
                ],
                'series' => $series,
            ]
        ]);

        ?>

    </div>

</div>

<?php

$js1 = <<<JS


JS;

$this->registerJs($js1);
?>