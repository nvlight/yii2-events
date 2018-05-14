<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\widgets\ActiveField;
use app\components\AuthLib;
use app\models\Type;
use app\models\Category;
use app\components\Debug;
use yii\widgets\Pjax;

$this->title = 'Events | Категории';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page post'], 'keywords');
?>

    <div class="bill-inset">
        <div class="page-caption clearfix">
            <h2 class="pull-left" >Страница записей</h2>
        </div>

        <div class="page-hr"></div>

        <div class="page-content">

            <div class="row">

                <div class="col-md-12">

                    <?php if (Yii::$app->session->hasFlash('addPost')) : ?>
                    <?php $success = Yii::$app->session->getFlash('success') === 'yes' ? 'success' : 'danger' ?>
                        <h4 class="alert-<?=$success?> p10 m015" >
                            <?php
                                echo Yii::$app->session->getFlash('addPost')
                                //echo Debug::d(Yii::$app->session->getFlash('addPost'));
                            ?>
                        </h4>
                    <?php endif; ?>

                    <div class="col-md-6">
                    <section class="addEvent">
                        <header>
                            <h4>Добавить событие</h4>
                        </header>
                        <div class="inner">

                            <?php $form = ActiveForm::begin([
                                'method'=>'post',
                                'action' => ['/post/add-event'],
                                'options' => [
                                    'class' => 'addEvent',
                                ]
                            ]); ?>

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
                            <?php ActiveForm::end(); ?>
                        </div>
                    </section>
                </div>

                    <div class="col-md-6">

                        <section class="addCat">
                            <header>
                                <h4>Добавить категорию</h4>
                            </header>
                            <div class="inner">
                                <?php $form = ActiveForm::begin([
                                    'action' => ['/post/add-category'],
                                    'options' => [
                                        'class' => 'addCategory',
                                    ]
                                ]); ?>

                                <div class="form-group">
                                    <label class="control-label" for="dropDownId_3">Существующие категории</label>
                                    <?php echo Html::dropDownList(
                                        'select', '',
                                        Category::find()->select(['name','id'])
                                            ->where(['i_user' => $_SESSION['user']['id']])
                                            ->indexBy('id')->orderBy(['id' => SORT_DESC])->column(),
                                        ['id' => 'dropDownId_3', 'class' => 'types_class', ]
                                        ); //->label('Категория',['for' => 'dropDownId_3']);
                                    ?>
                                </div>

                                <?= $form->field($caregory, 'name')->label('Введите название') ?>
                                <?= $form->field($caregory, 'limit')->label('Введите лимит') ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-gg']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>

                        </section>

                    </div>

                </div>

                <div class="col-md-12">

                    <div class="col-md-6">
                        <section class="editCat">
                            <header>
                                <h4>Редактировать категорию</h4>
                            </header>
                            <div class="inner">

                                <?php $form = ActiveForm::begin([
                                    'action' => ['/post/change-category'],
                                    'options' => [
                                        'class' => 'changeCategory',
                                    ]
                                ]); ?>

                                <?php
                                $params = [
                                    'class' => 'dropDownClass_2',
                                    'id' => 'dropDownId_2',
                                    'prompt' => 'Выберите категорию',
                                ];

                                echo $form->field($event, 'i_cat')->dropDownList(
                                    Category::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
                                    $params
                                )->label('Категория');

                                echo $form->field($caregory, 'name',[
                                        'inputOptions' => [
                                            'id' => 'changeCat-name',
                                        ],]
                                    )
                                    ->label('Введите название',['for' => 'changeCat-name'])

                                ?>
                                <?= $form->field($caregory, 'limit',[
                                    'inputOptions' => [
                                        'id' => 'changeCat-limit',
                                    ],])
                                    ->label('Введите лимит',['for' => 'changeCat-limit'])
                                ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary btn-gg']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>

                            </div>
                        </section>
                    </div>

                    <div class="col-md-6">
                        <section class="editCat">
                            <header>
                                <h4>Добавить тип события</h4>
                            </header>
                            <div class="inner">

                                <?php
                                $typeParams = [
                                    //'prompt' => 'Выберите категорию'
                                    'class' => 'dropDownType_Class',
                                    'id' => 'dropDownType_Id',
                                    'prompt' => 'Выберите категорию'
                                ];
                                ?>
                                <?php
                                $response = '';
                                //Pjax::begin([]);

                                $typeForm = ActiveForm::begin([
                                    //'action' => ['/post/add-type'],
                                    'options' => [
                                        'class' => 'addType',
                                        'data' => ['pjax' => true],
                                    ]
                                ]); ?>

                                <div class="form-group field-type-curr required">
                                    <label class="control-label" for="types_id2">Существующие типы событий</label>
                                    <?php echo Html::dropDownList(
                                        'select', '',
                                        Type::find()->select(['name','id'])->where(['i_user' => $_SESSION['user']['id']])->indexBy('id')->column(),
                                        ['id' => 'types_id2', 'class' => 'types_class', ]
                                    );
                                    ?>
                                    <div class="help-block"></div>
                                </div>

<!--                                <div class="form-group">-->
<!--                                    --><?php ////echo Html::a("Показать дату", ['post/index'], ['class' => 'btn btn-lg btn-success']) ?>
<!--                                    --><?php ////echo Debug::d($response,'$response'); ?>
<!--                                </div>-->

                                <?= $typeForm->field($type, 'name')  ?>
                                <?= $typeForm->field($type, 'color') ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-gg']) ?>
                                </div>
                                <?php
                                    ActiveForm::end();
                                    //Pjax::end();
                                ?>

                            </div>
                        </section>
                        <?php
                            //echo $randomString;
                            //echo $randomKey;
                            //$this->render('multiple', compact('randomString', 'randomKey'));
                        ?>
                    </div>

                </div>

            </div>

        </div>

    </div>

<?php
$js1 = <<<JS
$('form.addCategory').on('beforeSubmit', function(e){
	var data = $(this).serialize();
	$.ajax({
		url: '/post/add-category',
		type: 'POST',
		data: data,
		success: function(res){
        //console.log(res);
			var np = $.parseJSON(res);
			if (np['success'] === 'yes'){
            //console.log('add meta is done!');
				$('#dropDownId_1,#dropDownId_2,#dropDownId_3').append($('<option>', {
					value: np['id'],
					text: np['name']
				}));
				$('form.addCategory').trigger( 'reset' );
				alert(np['message']);
			} else{
				alert(np['message']);
			}
		},
		error: function(res){
			console.log(res);
		}
	});
	return false;
});
JS;

$js2 = <<<JS
$('form.changeCategory').on('beforeSubmit', function(e){
 //e.preventDefault();
	var data = $(this); 
	var p1 = $($('input#changeCat-name')[0]).val();
	var p2 = $($('input#changeCat-limit')[0]).val();
	var p3 = $("#dropDownId_2").find(":selected").val();
	$.ajax({
		url: '/post/change-category',
		type: 'POST',
		data: {p1,p2,p3},
		success: function(res){
        //console.log(res);
			var np = $.parseJSON(res);
        //console.log(np['success']);
        //console.log(np['message']);
        //console.log(np);
			if (np['success'] === 'yes'){
            //console.log('add meta is done!');
            //$('form.changeCategory').trigger( 'reset' );
				$("#dropDownId_2 [value="+"'"+np['id']+"'"+"]").text(np['name']);
				$("#dropDownId_1 [value="+"'"+np['id']+"'"+"]").text(np['name']);
				$("#dropDownId_3  [value="+"'"+np['id']+"'"+"]").text(np['name']);
				alert(np['message']);
			}  
		},
		error: function(res){
			console.log(res);
		}
	});
	return false;
});
JS;

$js3 = <<<JS
$('form.addType').on('beforeSubmit', function(e){
	var data = $(this).serialize();
	$.ajax({
		url: '/post/add-type',
		type: 'POST',
		data: data,
		success: function(res){
			var np = $.parseJSON(res);
            //console.log(np);
			if (np['success'] === 'yes'){
			    alert(np['message']);
				$('.types_class').append($('<option>', {
					value: np['id'],
					text: np['name']
				}));
				$(".types_class > option[value="+np['id']+"]").attr('selected','selected');
			}   
		},
		error: function(res){
			console.log(res);
		}
	});
	return false;
});
JS;

$js4 = <<<JS
$('form.addEvent').on('beforeSubmit', function(e){
	var data = $(this).serialize();
	$.ajax({
		url: '/post/add-event',
		type: 'POST',
		data: data,
		success: function(res){
			var np = $.parseJSON(res);
			console.log(np);
			alert(np['message']);
		},
		error: function(res){
			console.log(res);
		}
	});
	return false;
});
JS;

$this->registerJs($js1);
$this->registerJs($js2);
$this->registerJs($js3);
$this->registerJs($js4);

?>