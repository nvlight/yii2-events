<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\Debug;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HumanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поиск кино';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="human-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo Debug::d($this->params['breadcrumbs'],'breadcrumbs') ?>

    <p>
        <?= Html::a('Создать кино', ['video/index'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php

    ?>

<div class="search2">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        $youtube_cats = \app\models\Categoryvideo::find()->where(['i_user' => $_SESSION['user']['id']])->asArray()->all();
        // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
        //$youtube_cats = (array)$youtube_cats;
        //echo Debug::d($youtube_cats,'$youtube_cats');
        //$youtube_cats[] = [ 'id' => 0, 'name' => 'Выберите категорию'];
        $youtube_catsAh = ArrayHelper::map($youtube_cats,'id','name');
    ?>
    <?= $form->field($model, 'i_cat')
        ->dropDownList($youtube_catsAh,['id' => 'ytCat'])->label('Выберите категорию'); ?>

    <?= $form->field($model, 'title') ?>
    <?= $form->field($model, 'duration') ?>

    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <?php if($rs): ?>
        <?php echo Debug::d(($rs),'count(rs)'); ?>
    <?php endif;  ?>

</div><!-- search3 -->