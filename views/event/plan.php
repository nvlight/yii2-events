<?php
    //echo \app\components\Debug::d($catPlans,'catPlans');
use yii\helpers\Html;

$this->title = 'Events | Планирование';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events,Page plan'], 'keywords');
?>

<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left" >Страница планирования</h2>

    </div>
    <div class="page-hr"></div>
    <?php

    ?>
    <div class="page-content">
        <div class="rashodi clearfix mb4">
            <div class="header pull-left">
                <h4>Расходы</h4>
            </div>
            <div class="main pull-right">
                <p>Общий остаток: <span class="text-success"><?=Html::encode($diff_main)?> Р</span> </p>
            </div>
        </div>

        <div class="rashodi">

            <?php foreach ($catPlans as $cpk => $cpv): ?>

                <?php
                    //echo $cpv['p11'] . ' : ' . $cpv['p12'] . " : " . $cpv['p2'];
                    $cp1 = abs(intval($cpv['p11']-$cpv['p12']));
                    $cp1 = abs($cpv['p12']);
                    $cp2 = intval($cpv['p2']); if ($cp2 === 0) { continue; }
                    $cp3 = $cp2 - $cp1;
                    $procent = 100 * ($cp1/$cp2);
                    switch ($procent) {
                        case $procent < 50 : $procent_class = ' progress-bar-success'; break;
                        case (($procent >= 50) and ($procent <= 75)) : $procent_class = ' progress-bar-warning'; break;
                        case ($procent >= 75) : $procent_class = ' progress-bar-danger'; break;
                        default: $procent_class = "progress-bar-success";
                    }
                    // узнаем класс для расходов и осталось
                    $nclass = 'success';
                    $nclass = mb_substr($procent_class,strlen(' progress-bar-'));
                    //
                    if ($procent > 100) { $procent = 100; }
                    // осталось или перебор
                    $ostalos = "осталось";
                    if ($cp3 < 0) { $ostalos = "превышен на"; }
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="progress progress-striped1">
                            <div class="progress-bar <?=$procent_class?>" style="width: <?=$procent?>%">
                                <span class="sr-only"><?=$cpv['name']?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="_ngcontent-c20">

                            <span  class="text-<?=$nclass?>"><?=$cp1?></span>
                            из
                            <span  class="text-primary"><?=$cp2?></span>
                            |
                            <?=$ostalos?>
                            <span  class="text-<?=$nclass?>">
                                <?=$cp3?> Р
                            </span>
                        </p>
                    </div>
                </div>
        <?php endforeach; ?>
        </div>
    </div>

</div>