<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;
use app\models\Type;
use yii\helpers\ArrayHelper;
use app\models\Event;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php

    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'i_cat')->dropDownList(
        Category::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
        ['id' => 'changeEventModal_catId', ]
    )->label('Категория'); ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'summ')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList(
        Type::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
        [['id' => 'changeEventModal_typeId',] ]
        )->label('Тип события')
    ?>

    <?php
    echo $form->field($model, 'dtr')->widget(DatePicker::className(),[
        'language' => 'ru',
        'value' =>  Yii::$app->formatter->asDate(date('Y-m-d')),
        'options' => ['placeholder' => 'выберите дату', 'id' => 'updateEvent_datePicker'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton( (\Yii::$app->controller->action->id == 'create') ? 'Создать' : 'Обновить',
            ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
