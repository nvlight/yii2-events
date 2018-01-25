<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Human */

$this->title = 'Create Human';
$this->params['breadcrumbs'][] = ['label' => 'Humans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="human-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
