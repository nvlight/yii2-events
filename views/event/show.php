<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 11.02.2018
 * Time: 21:01
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Debug;

$this->title = 'Events | Просмотр события';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Show event page'], 'keywords');


//echo Debug::d($rs,'rs');
$rs['catname'] = $rs['category']['name'];
$rs['typename'] = $rs['types']['name'];

?>

<div class="human-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $rs['id'] ], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $rs['id']], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $rs,
        'attributes' => [
            'id',
            "catname",
            'desc',
            'summ',
            "dtr",
            "typename",
        ],
    ]) ?>

</div>
