<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 29.03.2018
 * Time: 17:22
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

$this->title = 'Events | Кино';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page videos'], 'keywords');
?>

<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left">Кино</h2>
    </div>

    <div class="page-hr">
        <hr>
    </div>
    <div class="page-content">

        <div class="row">
            <?php
                $videos = [];
                $videos = $all;

            ?>
            <?php if (isset($videos) && is_array($videos) && count($videos)): ?>
                <?php foreach ($videos as $k => $v): ?>
                    <div class="col-md-4">
                        <iframe
                            width="350" height="250"
                            src="https://www.googleapis.com/youtube/v3/videos?id=<?=$v->link?>&key=<?=Yii::$app->params['youtube_api_key_1']?>
                                &part=snippet,contentDetails,statistics,status"
                            frameborder="0" allow="autoplay; encrypted-media"
                            allowfullscreen>
                        </iframe>
                        <div class="row">
                            <div class="col-md-8"><h5><?=$v->description?></h5></div>
                            <div class="col-md-4 text-right"><h5><?=$v->categoryvideo['name']?></h5></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-6">
                <hr>
                <h3>Добавление видео</h3>
                <div class="video-main">

                    <?php if (\Yii::$app->session->hasFlash('addVideo')): ?>
                        <p class="alert-success p10 fz16"><?=\Yii::$app->session->getFlash('addVideo')?></p>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin(); ?>

                    <?php
                    // Всё что нужно знать о LGA2011.(Pt. 2) Материнские платы.
                    // test = c50F77tGJJk
                    $videocategory = \app\models\Categoryvideo::findAll(['i_user' => $_SESSION['user']['id']]);
                    $arr_videocategory = ArrayHelper::map($videocategory,'id','name');
                    ?>

                    <?= $form->field($model, 'i_cat')->dropDownList($arr_videocategory,['id' => 'videocategory'])
                        ->label('Выберите категорию'); ?>
                    <?= $form->field($model, 'description') ?>
                    <?= $form->field($model, 'link') ?>
                    <?= $form->field($model, 'dt_publish')->widget(DatePicker::className(),[
                        'language' => 'ru',
                        'name' => 'dt_publish',
                        'type' => 2,
                        "value" =>  date('d-m-Y'),
                        'options' => ['placeholder' => 'выберите дату', 'id' => 'dt_publish'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'todayHighlight' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ]); ?>
                    <?= $form->field($model, 'duration') ?>
                    <?= $form->field($model, 'note') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div><!-- video-main -->
            </div>
        </div>

    </div>

</div>