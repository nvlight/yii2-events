<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 15.05.2018
 * Time: 19:30
 */

use kartik\date\DatePicker;
use app\models\Event;

?>

<?php

$eventMain = new Event();

$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($eventMain, 'dtr')->widget(DatePicker::className(),[
    'language' => 'ru',
    'name' => 'check_issue_date',
    //'value' =>  Yii::$app->formatter->asDate(date('Y-m-d')),
    'value' => '1999-12-15',
    'options' => ['placeholder' => 'выберите дату', 'id' => 'addEventModal_datePicker'],
    'pluginOptions' => [
        'autoclose'=>true,
        'todayHighlight' => true,
        'format' => 'yyyy-mm-dd',
    ]
]);

\yii\bootstrap\ActiveForm::end();
?>
