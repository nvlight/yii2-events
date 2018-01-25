<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Human */

$this->title = 'Update Human: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Humans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="human-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
