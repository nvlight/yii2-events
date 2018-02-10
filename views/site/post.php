<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\widgets\ActiveField;

$this->title = 'Events | Категории';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page post'], 'keywords');
?>

<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left" >Страница записей</h2>
        <span class="reload pull-right">
                <i class="fa fa-refresh" aria-hidden="true"></i>
        </span>

    </div>
    <div class="page-hr">
        <hr>
    </div>
    <div class="page-content">

        <div class="row">

            <div class="col-md-6">
                <section class="addEvent">
                    <header>
                        <h4>Добавить событие</h4>
                    </header>
                    <div class="inner">

                        <?php $form = ActiveForm::begin([
                            'method'=>'post',
                            'action' => ['/site/add-event'],
                            'options' => [
                                'class' => 'addEvent',
                            ]
                        ]); ?>

                        <?php
                            // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
                            $cats2 = ArrayHelper::map($cats,'id','name');
                            $params = [
                                //'prompt' => 'Выберите категорию'
                                'class' => 'dropDownClass_1',
                                'id' => 'dropDownId_1'
                            ];
                        ?>
                        <?= $form->field($event, 'i_cat')->dropDownList($cats2,$params)->label('Выберите категорию'); ?>

                        <?= $form->field($event,'type',[
                            'template' => '<label for="">Выберите тип</label><div>{input}</div>',
                        ])->radioList(
                            [1 => 'Доход', 2 => 'Расход'],
                            [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $ch = '';
                                    if ($index === 0) {
                                        $ch = "checked=''";
                                    }
                                    $return = '<label>';
                                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3"' . " {$ch} " . ' >'."\n";
                                    $return .= '<i class="fa fa-circle-o fa-2x"></i>' ."\n" .
                                                '<i class="fa fa-dot-circle-o fa-2x"></i>' ."\n";
                                    $return .= '<span>' . ucwords($label) . '</span>' ."\n";
                                    $return .= '</label><br/>';

                                    return $return;
                                }
                            ]
                        ); ?>

                        <?php
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
                            'action' => ['/site/tryaction'],
                            'options' => [
                                'class' => 'addCategory',
                            ]
                        ]); ?>

                        <?= $form->field($model, 'name')->label('Введите название') ?>
                        <?= $form->field($model, 'limit')->label('Введите лимит') ?>

                        <div class="form-group">
                            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-gg']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>

                </section>

            </div>

            <div class="col-md-6">
                <section class="editCat">
                    <header>
                        <h4>Редактировать категорию</h4>
                    </header>
                    <div class="inner">

                        <?php $form = ActiveForm::begin([
                            'action' => ['/site/tryaction'],
                            'options' => [
                                'class' => 'changeCategory',
                            ]
                        ]); ?>

                        <?php
                        // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
                        $cats2 = ArrayHelper::map($cats,'id','name');
                        $params = [
                            //'prompt' => 'Выберите категорию'
                            'class' => 'dropDownClass_2',
                            'id' => 'dropDownId_2'

                        ];
                        ?>
                        <?= $form->field($event, 'i_cat')->dropDownList($cats2,$params)->label('Выберите категорию'); ?>

                        <?= $form->field($model, 'name',[
                                'inputOptions' => [
                                    'id' => 'changeCat-name',
                                ],]
                                )->label('Введите название')

                        ?>
                        <?= $form->field($model, 'limit',[
                            'inputOptions' => [
                                'id' => 'changeCat-limit',
                            ],])->label('Введите лимит') ?>

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
                            $types2 = ArrayHelper::map($types,'id','name');
                            $typeParams = [
                                //'prompt' => 'Выберите категорию'
                                'class' => 'dropDownType_Class',
                                'id' => 'dropDownType_Id'
                            ];
                        ?>
                        <?php $typeForm = ActiveForm::begin([
                            'action' => ['/site/tryaction'],
                            'options' => [
                                'class' => 'addType',
                            ]
                        ]); ?>

                        <div class="form-group field-type-curr required">
                            <label class="control-label" for="type-curr">Существующие типы событий</label>
                            <?php echo Html::dropDownList('select', '', $types2,['id' => 'types_id', 'class' => 'types_class']); ?>
                            <div class="help-block"></div>
                        </div>

                        <?= $typeForm->field($type, 'name') ?>
                        <?= $typeForm->field($type, 'color') ?>

                        <div class="form-group">
                            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-gg']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </section>
            </div>

        </div>





    </div>

</div>

<?php
$js1 = <<<JS
 $('form.addCategory').on('beforeSubmit', function(e){
 //e.preventDefault();
 var data = $(this).serialize();
 //alert('add category...')
 console.log('add category...');
 $.ajax({
     url: '/web/site/add-category',
     type: 'POST',
     data: data,
     success: function(res){
        console.log(res);
        var np = $.parseJSON(res);
        // console.log(np['success']);
        // console.log(np['message']);
        // console.log(np);
        if (np['success'] === 'yes'){
            console.log('add meta is done!');
            $('#dropDownId_1,#dropDownId_2,#dropDownId_3').append($('<option>', {
                value: np['id'],
                text: np['name']
            }));
            $('form.addCategory').trigger( 'reset' );
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
 //alert('add category...')
 console.log('change category...');
 $.ajax({
     url: '/web/site/change-category',
     type: 'POST',
     data: {p1,p2,p3},
     success: function(res){
        console.log(res);
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
        url: '/web/site/add-type',
        type: 'POST',
        data: data,
        success: function(res){
            var np = $.parseJSON(res);
            console.log(np);
            if (np['success'] === 'yes'){
                $('#types_id').append($('<option>', {
                    value: np['id'],
                    text: np['name']
                }));
                $("#types_id > option[value="+np['id']+"]").attr('selected','selected');
            }   
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

?>