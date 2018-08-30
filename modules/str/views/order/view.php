<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Debug;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\str\models\Order */

$this->title = 'title';
$m2 = \app\modules\str\models\Order::find()->where(['id' => $model->id])->with('project')->with('room')->with('job')->one();
//echo Debug::d($m2,'$m2'); die;
if ($m2){
    $links[] = [$m2->project->name,Url::to(['/str/project/view?id='.$m2->project->id],true), 'Проект' ];
    $links[] = [$m2->room->name,Url::to(['/str/room/view?id='.$m2->room->id],true), 'Комната' ];
    $this->title = $m2->job->name;
}
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <?php
        //echo Debug::d($links,'$links');
        //echo Debug::d($m2, 'model');
        //echo Debug::d($model,'model');
    ?>

    <?php if (isset($links) && is_array($links) && count($links) ): ?>
        <?php foreach($links as $k => $v): ?>
            <p><?=$v[2]?>: <?= Html::a($v[0], $v[1], ['class' => '']) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <p>
        <span class="h3">Вид работы: <strong><?= Html::encode($this->title) ?></strong></span>
    </p>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'id_project',
                    'value' => function ($data) {
                        return $data->project->name;
                    },
                ],
                [
                    'attribute' => 'id_room',
                    'value' => function ($data) {
                        return $data->room->name;
                    },
                ],
                [
                    'attribute' => 'id_job',
                    'value' => function ($data) {
                        return $data->job->name;
                    },
                ],
                'volume',
                'price1',
                'price2',
                //'price3',
                'summ1',
                'summ2',
            ],
        ])
    ?>

</div>
