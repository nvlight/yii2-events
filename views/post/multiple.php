<?php

use yii\widgets\Pjax;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-sm-12 col-md-6">
        <?php Pjax::begin(); ?>
        <?= Html::a("Новая случайная строка", ['post/multiple'], ['class' => 'btn btn-lg btn-primary']) ?>
        <h3><?= $randomString ?></h3>
        <?php Pjax::end(); ?>
    </div>

    <div class="col-sm-12 col-md-6">
        <?php Pjax::begin(); ?>
        <?= Html::a("Новый случайный ключ", ['post/multiple'], ['class' => 'btn btn-lg btn-primary']) ?>
        <h3><?= $randomKey ?><h3>
        <?php Pjax::end(); ?>
    </div>
</div>

