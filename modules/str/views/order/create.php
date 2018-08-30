<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\str\models\Order */

$this->title = 'Создать работу';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
