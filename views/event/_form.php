<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;
use app\models\Type;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php
        $cats = Category::find()->where(['i_user' => $_SESSION['user']['id']])->all();
        $cats3 = ArrayHelper::map($cats,'id','name');
        $params1 = [
            'id' => 'changeEventModal_catId'
        ];
        $types = Type::find()->all();
        $types3 = ArrayHelper::map($types,'id','name');
        $params2 = [
            'id' => 'changeEventModal_typeId'
        ];
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'i_cat')->dropDownList($cats3,$params1)->label('Выберите категорию'); ?>
    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'summ')->textInput() ?>
    <?= $form->field($model, 'type')->dropDownList($types3,$params2)->label('Выберите тип события'); ?>
    <?= $form->field($model, 'dtr')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton( (\Yii::$app->controller->action->id == 'create') ? 'Создать' : 'Обновить',
            ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
