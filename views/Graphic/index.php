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

<!--        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>-->

        <?php
        $pie_data = [];
        if (isset($ob_rs) && is_array($ob_rs) && count($ob_rs)){
            $i = 4;
            foreach ($ob_rs as $k => $v){
                $pie_data[] = [
                    'name' => $v['nm'],
                    'y' => intval($v['sm']),
                    //'color' => new JsExpression("Highcharts.getOptions().colors[$i]")
                    'color' => '#' . $v['cl'],
                ];
                $i++;
            }
        }

        $series_pie_1 = [
            'type' => 'pie',
            'name' => 'Сумма по типу',
            'data' => [
                [
                    'name' => 'До',
                    'y' => 13,
                    'color' => new JsExpression('Highcharts.getOptions().colors[0]'), // Jane's color
                ],
            ],
            'center' => [150, 100],
            'size' => 100,
            'showInLegend' => false,
            'dataLabels' => [
                'enabled' => false,
            ],
        ];
        if (count($pie_data)) {
            $series_pie_1['data'] = $pie_data;
        }
        $avg = [
            'type' => 'spline',
            'name' => 'Среднее',
            'data' => [3, 2.67, 3, 6.33, 3.33],
            'marker' => [
                'lineWidth' => 2,
                'lineColor' => new JsExpression('Highcharts.getOptions().colors[3]'),
                'fillColor' => 'white',
            ],
        ];
        $labels1 = [
            'html' => 'Сумма по типам категорий',
            'style' => [
                'left' => '100px',
                'top' => '30px',
                'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
            ],
        ];

        // сделаем массив сериес из наших вновь прибывших данных
//        'series' => [
//            [
//                'name' => 'Доходы',
//                'color' => '#5CB85C',
//                'data' => [30000, 10000, 15000, 20000, 15000],
//            ],
        //
        $months = [1,2,3,4,5,5,6,7,8,9,10,11,12];
        $tp_ids = [1,2,3,4]; $tp = ['','Доход','Расход','Долг','Вклад'];
        $na = []; $years = [2018];
        foreach($years as $year){
            foreach($months as $mk => $mv){
                foreach($tp_ids as $tpk => $tpv){

                    foreach($q_get_years_with_months as $dk => $dv){
                        //
                        if ($year == $dv['dtr'] && ($mv == $dv['mnth']) && $tpv == $dv['tp'] ){

                            $na[$tpv]['name'] = $tp[$tpv];
                            $na[$tpv]['color'] = '#'. $dv['cl'];
                            $na[$tpv]['data'][$year][$dv['mnth']] = intval($dv['sm']);
                        }
                    }
                }
            }
        }
        // сортируем сам массив, а потом по ключам внутренний массив inner
        // после добавляем недостающие ключи и обнуляем их, готово!
        // еще раз сортируем $v['data']
        ksort($na);
        //echo Debug::d($na,'$na');
        foreach($na as $k => &$v){ foreach($v['data'] as $kk => &$vv) { ksort($vv); } }
        foreach($na as $k => &$v){
            foreach($v['data'] as $kk => &$vv) {

                foreach ($months as $mk => $mv) {
                    if (!array_key_exists($mv, $vv)) {
                        $vv[$mv] = 0;
                    }
                }
            }
        }
        foreach($na as $k => &$v){ foreach($v['data'] as $kk => &$vv) { ksort($vv); } }
        // почти конец, осталось теперь объеденить массивы с годами в 1 массив
        $nac = $na;
        foreach($nac as $k => &$v){
            $tmp = [];
            foreach($v['data'] as $kk => &$vv) {

                foreach ($vv as $kkk => $vvv) {
                    $tmp[] = $vvv;
                }
            }
            $v['data'] = $tmp;
        }
        //echo Debug::d(count($nac),'count($na)');
        //echo Debug::d($na,'$na');
        //echo Debug::d($nac,'$nac',1); //die;
        // наконец-то ! создаем массив $series;
        $series = $series2 = [];
        foreach($nac as $k => &$v){
            $series[] = &$v;
            //echo Debug::d($v,$k. ' current');
        }//die;
        // добавить среднее и кусок серии с общими цифрами!
        $series2[] = $avg;
        $series2[] = $series_pie_1;
        //echo Debug::d($series,'series');

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
//                        ], [
//                            'name' => 'Internet Explorer',
//                            'y' => 11.84
//                        ], [
//                            'name' => 'Firefox',
//                            'y' => 10.85
//                        ], [
//                            'name' => 'Edge',
//                            'y' => 4.67
//                        ], [
//                            'name' => 'Safari',
//                            'y' => 4.18
//                        ], [
//                            'name' => 'Sogou Explorer',
//                            'y' => 1.64
//                        ], [
//                            'name' => 'Opera',
//                            'y' => 1.6
//                        ], [
//                            'name' => 'QQ',
//                            'y' => 1.2
//                        ], [
//                            'name' => 'Other',
//                            'y' => 2.61
//                        ]
//                    ]
                    'data' => $pie_data,
                ]]
            ]
        ]);

//        echo Highcharts::widget([
//            'scripts' => [
//                'modules/exporting',
//                'themes/grid-light',
//            ],
//            'options' => [
//                'chart' => [
//                    'type' => 'pie',
//                ],
//                'title' => [
//                    'text' => 'Общая сводка ресурсов',
//                ],
//                'xAxis' => [
//                    'categories' => ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь' , 'Октябрь', 'Ноябрь', 'Декабрь'],
//                ],
//                'labels' => [
//                    'items' => [
//                        $labels1,
//                    ]
//                ],
//                'plotOptions' => [
//                    'pie' => [
//                        'allowPointSelect' => true,
//                        'cursor' => 'pointer',
//                        'dataLabels' => [
//                            'enabled' => true,
//                        ],
//                    ]
//                ],
//                'series' => $series2,
//            ]
//        ]);
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