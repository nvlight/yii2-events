<?php
    //echo Yii::$app->request->get('id');
    //echo \app\components\Debug::d($event,'event');
?>

<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left">
            <a href="<?=\yii\helpers\Url::to(['/site/history'])?>"><i class="fa fa-arrow-left" style="color: #52BCD3;" aria-hidden="true"></i></a>
            Страница записи №<?=Yii::$app->request->get('id')?>
        </h2>

    </div>
    <div class="page-hr">
        <hr>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <!-- <div style="border: 1px solid #ccc">chich</div> -->
                <div class="gg-inset">
                    <?php
                        switch ($event[0]->type){
                            case 1: { $evtype[1] = ['dohod', 'доход','#fff']; $evtypeid = 1;  break; }
                            case 2: { $evtype[2] = ['rashod',  'расход','#fff']; $evtypeid = 2; break; }
                            default:{ $evtype[3] = ['type_undefined', 'просто событие','#fff']; $evtypeid = 0;}
                        }
                    ?>
                    <header class="<?=$evtype[$evtypeid][0]?>">
                        <span style="color:<?=$evtype[$evtypeid][2]?>"><?=$evtype[$evtypeid][1]?></span>
                    </header>
                    <section>
                        <ul>
                            <li>Сумма: <span><?=$event[0]->summ?></span></li>
                            <li>Категория: <span><?=$event[0]['category']->name?></span></li>
                            <li>Описание: <span><?=$event[0]->desc?></span></li>
                        </ul>
                    </section>
                    <footer>
                        <?=$event[0]->dtr?>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>
