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
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, View event page'], 'keywords');


//echo Debug::d($rs,'rs');
//$rs['catname'] = $rs['category']['name'];
//$rs['typename'] = $rs['types']['name'];

?>

<div class="event-view">

    <h3><?= Html::encode(trim(explode('|',$this->title)[1])) ?></h3>

    <p>
        <?= Html::a('Создать', ['event/create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?= Html::a('Обновить', ['event/update', 'id' => $rs['id'] ], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['event/delete', 'id' => $rs['id'],], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить запись?',
                'method' => 'get',
                'params' => [
                    'id' => $rs['id']
                ]
            ],
        ]) ?>

    </p>

    <?= DetailView::widget([
        'model' => $rs,
        'attributes' => [
            [
                'label' => '№',
                'attribute' => 'id',
            ],
            [
                'label' => 'Категория',
                'attribute' => 'i_cat',
                'value' => $rs->category->name
            ],
            [
                'label' => 'Описание',
                'attribute' => 'desc',
            ],
            [
                'label' => 'Сумма',
                'attribute' => 'summ',
            ],
            [
                'label' => 'Тип',
                'attribute' => 'type',
                'value' => $rs->types->name
            ],
            [
                'label' => 'Дата',
                'attribute' => 'dtr',
            ],
        ],
    ]) ?>

</div>
