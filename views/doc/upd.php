<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 24.03.2018
 * Time: 18:44
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Events | Документы - Обновление';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page update documents'], 'keywords');

?>


<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left" >Страница документов</h2>
    </div>

    <div class="page-hr">
        <hr>
    </div>
    <div class="page-content">

        <div class="load_user_documents">

            <div class="row">

                <div class="col-md-6">

                    <?php if (\Yii::$app->session->hasFlash('changeFile')): ?>
                        <p class="alert-success p10 fz16"><?=\Yii::$app->session->getFlash('changeFile')?></p>
                    <?php endif; ?>

                    <h3>Правка мета-данных файла</h3>
                    <?php $form = ActiveForm::begin([
                        'options' => ['method' => 'post']
                    ]);
                    ?>

                    <?=$form->field($model,'filename')->textInput()->label('Имя') ?>
                    <?=$form->field($model,'notice')->textInput()->label('Примечание') ?>

                    <?= Html::submitButton( 'Обновить', ['class' => 'btn btn-success']) ?>

                    <?php ActiveForm::end();  ?>
                </div>

            </div>

        </div>

    </div>
</div>

