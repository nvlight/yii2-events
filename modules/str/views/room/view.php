<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\modules\str\models\OrderSearch;
use app\modules\str\models\Order;
use app\components\Debug;
use yii\data\ActiveDataProvider;
use app\modules\str\models\Room;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\str\models\Room */

$m2 = \app\modules\str\models\Room::find()->where(['id' => $model->id])->with('project')->one();
//echo Debug::d($m2,'m2');
if ($m2){
    $links[] = [$m2->project->name,Url::to(['/str/project/view?id='.$m2->project->id],true), 'Проект' ];
    //$links[] = [$m2->room->name,Url::to(['/str/room/view?id='.$m2->room->id],true), 'Комната' ];
}

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rooms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-view">

    <?php if (isset($links) && is_array($links) && count($links) ): ?>
        <?php foreach($links as $k => $v): ?>
            <p><?=$v[2]?>: <?= Html::a($v[0], $v[1], ['class' => '']) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <p>
        <span class="h3">Комната: <strong><?= Html::encode($this->title) ?></strong></span>
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
        $q = Room::find()->where(['id' => $model->id])->with('project')->one();
        //$q = $q->all();
        //echo Debug::d($q,'q'); die;

        $modelRoom = new ActiveDataProvider([
            'query' => $q,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        echo DetailView::widget([
            'model' => $q,
            'attributes' => [
                'id',
                [
                    'attribute' => 'id_parent',
                    'value' => function ($data) {
                        return $data->project->name;
                    },
                ],
                'name',
                'height',
                'perimeter',
                'wall_count',
                's_roof',
                's_floor',
            ],
        ]);
    ?>

    <p>
        <?= Html::a('Добавить тип работы', ['/str/order/create?id='.$model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
        $searchModel = new OrderSearch();

        $dataProvider = Order::find()->where(['id_room' => $model->id])
            ->with('project')->with('room')->with('job'); //->all(); // ->asArray()

        //$dataProvider = $dataProvider->all();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //echo Debug::d($dataProvider,'dataProvider'); die;
        //$dataProvider[0]->project['name'];

        //data provider
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()->where(['id_room' => $model->id])
                ->with('project')->with('room')->with('job'),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [ 'class' => 'yii\grid\SerialColumn'],
            //'id',
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
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span> ', $url, [
                            'title' => Yii::t('app', 'view'),
                        ]);
                    },

                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> ', $url, [
                            'title' => Yii::t('app', 'update'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span> ', $url, [
                            'title' => Yii::t('app', 'delete'), 'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                    }

                ],

                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url ='/str/order/view?id='.$model->id;
                        return $url;
                    }

                    if ($action === 'update') {
                        $url ='/str/order/update?id='.$model->id;
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url ='/str/order/delete?id='.$model->id;
                        return $url;
                    }
                }
            ],

        ]
    ]);
    ?>

</div>
