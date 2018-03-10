<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

$this->title = 'Events | ';
if (\Yii::$app->controller->action->id == 'create') {
    $this->title .= 'Создание';
}else{
    $this->title .= 'Просмотр';;
}
$this->title .= ' события';

//echo \Yii::$app->controller->action->id;
//echo \app\components\Debug::d(Yii::$app,'yii-app');

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Show event page'], 'keywords');


?>
<div class="user-update">

    <h3><?= Html::encode(trim(explode('|',$this->title)[1])) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
