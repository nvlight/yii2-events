<?php

use yii\widgets\Pjax;
use yii\helpers\Html;
use app\models\Category;
use yii\widgets\ActiveForm;
use app\models\Type;
use app\components\Debug;
use kartik\date\DatePicker;

$event = new \app\models\Event();
$category = new Category();
$type = new Type();

?>

<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left" >Страница записей</h2>
    </div>

    <div class="page-hr"></div>

    <div class="page-content">

        <div class="row">

            <div class="col-md-12">
                <?//=Debug::d($_REQUEST,'request'); ?>
            </div>

            <div class="col-md-6">
                <section class="editCat">
                    <header>
                        <h4>Добавить тип события</h4>
                    </header>
                    <div class="inner">

                        <?php
                        Pjax::begin(['enablePushState' => false]);
                        $typeForm = ActiveForm::begin([
                            'action' => ['/post/add-type-pjax'],
                            'options' => [
                                'class' => 'addType',
                                'data' => ['pjax' => true],
                            ]
                        ]);
                        ?>

                        <?php if (Yii::$app->session->hasFlash('addType')) : ?>
                            <?php $success = Yii::$app->session->getFlash('success') === 'yes' ? 'success' : 'danger' ?>
                            <h4 class="alert-<?=$success?> p10" >
                                <?php echo Yii::$app->session->getFlash('addType') ?>
                            </h4>
                        <?php endif; ?>

                        <div class="form-group">
                            <?//=Debug::d($types,'types')?>
                            <label class="control-label" for="type-curr">Существующие типы событий</label>
                            <?php echo Html::dropDownList(
                                'select', '',
                                $types,
                                ['id' => 'types_id', 'class' => 'types_class', ]
                            );
                            ?>
                        </div>

                        <?= $typeForm->field($type, 'name')  ?>
                        <?= $typeForm->field($type, 'color') ?>

                        <div class="form-group">
                            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-gg']) ?>
                        </div>
                        <?php
                        ActiveForm::end();
                        Pjax::end();
                        ?>

                    </div>
                </section>
                <?php

                ?>
            </div>
            <div class="col-md-6">

                <section class="addCat">
                    <header>
                        <h4>Добавить категорию</h4>
                    </header>
                    <div class="inner">
                        <?php
                        Pjax::begin(['enablePushState' => false]);
                        $form = ActiveForm::begin([
                            'action' => ['/post/add-category-pjax'],
                            'options' => [
                                'class' => 'addCategory',
                                'data' => ['pjax' => true],
                            ]
                        ]);
                        ?>

                        <?php if (Yii::$app->session->hasFlash('addCategory')) : ?>
                            <?php $success = Yii::$app->session->getFlash('success') === 'yes' ? 'success' : 'danger' ?>
                            <h4 class="alert-<?=$success?> p10" >
                                <?php echo Yii::$app->session->getFlash('addCategory') ?>
                            </h4>
                        <?php endif; ?>

                        <div class="form-group">
                            <label class="control-label" for="type-curr">Существующие категории</label>
                            <?php echo Html::dropDownList(
                                'select', '',
                                $categories,
                                ['id' => 'categories_id', 'class' => 'types_class', ]
                            );
                            ?>
                        </div>

                        <?php echo $form->field($category, 'name')->label('Введите название') ?>
                        <?php echo $form->field($category, 'limit')->label('Введите лимит') ?>

                        <div class="form-group">
                            <?php echo Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-gg']) ?>
                        </div>
                        <?php
                        ActiveForm::end();
                        Pjax::end([]);
                        ?>
                    </div>

                </section>

            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <section class="editCat">
                    <header>
                        <h4>Редактировать категорию</h4>
                    </header>
                    <div class="inner">

                        <?php
                        Pjax::begin(['enablePushState' => false]);
                        $form = ActiveForm::begin([
                            'action' => ['/post/change-category-pjax'],
                            'options' => [
                                'class' => 'changeCategory',
                                'data' => ['pjax' => true],
                            ]
                        ]); ?>

                        <?php if (Yii::$app->session->hasFlash('changeCategory')) : ?>
                            <?php $success = Yii::$app->session->getFlash('success') === 'yes' ? 'success' : 'danger' ?>
                            <h4 class="alert-<?=$success?> p10" >
                                <?php echo Yii::$app->session->getFlash('changeCategory') ?>
                            </h4>
                        <?php endif; ?>

                        <?php

                        echo $form->field($event, 'i_cat')->dropDownList(
                            $categories,
                            ['class' => 'dropDownClass_2', 'id' => 'dropDownId_2',]
                        )->label('Категория');

                        echo $form->field($category, 'name',[
                                'inputOptions' => [
                                    'id' => 'changeCat-name',
                                ],]
                        )->label('Введите название',['for' => 'changeCat-name'])

                        ?>
                        <?= $form->field($category, 'limit',[
                            'inputOptions' => [
                                'id' => 'changeCat-limit',
                            ],])->label('Введите лимит',['for' => 'changeCat-limit'])
                        ?>

                        <div class="form-group">
                            <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary btn-gg']) ?>
                        </div>
                        <?php
                        ActiveForm::end();
                        Pjax::end([]);
                        ?>

                    </div>
                </section>
            </div>
            <div class="col-md-6">
                <section class="addEvent">
                    <header>
                        <h4>Добавить событие</h4>
                    </header>
                    <div class="inner">

                        <?php
                        Pjax::begin(['enablePushState' => false]);
                        $form = ActiveForm::begin([
                            'method'=>'post',
                            'action' => ['/post/add-event-pjax'],
                            'options' => [
                                'class' => 'addEvent',
                                'data' => ['pjax' => true],
                            ]
                        ]);
                        ?>

                        <?php if (Yii::$app->session->hasFlash('addEvent')) : ?>
                            <?php $success = Yii::$app->session->getFlash('success') === 'yes' ? 'success' : 'danger' ?>
                            <h4 class="alert-<?=$success?> p10" >
                                <?php echo Yii::$app->session->getFlash('addEvent') ?>
                            </h4>
                        <?php endif; ?>

                        <?php
                        $params = [
                            'class' => 'dropDownClass_1',
                            'id' => 'dropDownId_1',
                            'prompt'=>'Выберите категорию'
                        ];
                        echo $form->field($event, 'i_cat')->dropDownList(
                            Category::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
                            $params
                        )->label('Категория');

                        echo $form->field($event, 'type')->dropDownList(
                            Type::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
                            ['id' => 'types_id', 'class' => 'types_class', 'prompt'=>'Выберите тип события' ]
                        )->label('Тип события');

                        //echo $form->field($event, 'dtr')->widget(\yii\widgets\MaskedInput::className(), [ 'mask' => '99-99-9999', ]);
                        echo $form->field($event, 'dtr')
                            ->widget(DatePicker::className(),[
                                    'language' => 'ru',
                                    'name' => 'dp_2',
                                    'type' => 2,
                                    "value" =>  '16-11-2017',
                                    'options' => ['placeholder' => 'выберите дату', 'id' => 'changeEventModal_datePicker'],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy',
                                    ]
                                ]
                            );
                        ?>

                        <?= $form->field($event, 'summ')->label('Введите сумму') ?>
                        <?= $form->field($event, 'desc')->label('Введите описание') ?>

                        <div class="form-group">
                            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-gg']) ?>
                        </div>
                        <?php
                        ActiveForm::end();
                        Pjax::end([]);
                        ?>
                    </div>
                </section>
            </div>
        </div>

    </div>
</div>



