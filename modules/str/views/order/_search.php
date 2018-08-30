<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\str\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_room') ?>

    <?= $form->field($model, 'id_project') ?>

    <?= $form->field($model, 'id_job') ?>

    <?= $form->field($model, 'volume') ?>

    <?php // echo $form->field($model, 'price1') ?>

    <?php // echo $form->field($model, 'price2') ?>

    <?php // echo $form->field($model, 'price3') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
