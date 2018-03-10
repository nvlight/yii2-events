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
        //echo \app\components\Debug::d($model,'model');
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
        //$model['catname'] = $model['category']['name'];
        //$model['typename'] = $model['types']['name'];
        //echo $form->field($model, 'catname')->textInput(['maxlength' => true]);
        //echo $form->field($model, 'typename')->textInput();
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'i_cat')->dropDownList($cats3,$params1)->label('Выберите категорию'); ?>
    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'summ')->textInput() ?>
    <?= $form->field($model, 'dtr')->textInput() ?>
    <?= $form->field($model, 'type')->dropDownList($types3,$params2)->label('Выберите тип события'); ?>

    <div class="form-group">
        <?= Html::submitButton( (\Yii::$app->controller->action->id == 'create') ? 'Создать' : 'Обновить',
            ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
