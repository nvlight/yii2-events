<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\str\models\Room */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="room-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'height')->textInput() ?>

    <?= $form->field($model, 'perimeter')->textInput() ?>

    <?= $form->field($model, 'wall_count')->textInput() ?>

    <?= $form->field($model, 's_roof')->textInput() ?>

    <?= $form->field($model, 's_floor')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
