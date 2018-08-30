<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\str\models\Jobtypes;

/* @var $this yii\web\View */
/* @var $model app\modules\str\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'id_job')->textInput() ?>

    <?php
        $params = [
            'class' => 'form-control dropdown_class-id_job',
            'id' => 'dropdown_id-id_job',
            'prompt'=>'Выберите вид работы'
        ];
        echo $form->field($model, 'id_job')->dropDownList(
            Jobtypes::find()->select(['name','id'])->indexBy('id')->column(),
        $params
        )->label('Работа');

    ?>

    <?= $form->field($model, 'volume')->textInput() ?>

    <?php // $form->field($model, 'price1')->textInput() ?>

    <?php // $form->field($model, 'price2')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
