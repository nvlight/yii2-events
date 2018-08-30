<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
Use app\modules\str\models\Room;
use app\modules\str\models\RoomSearch;
use yii\debug\models\timeline\DataProvider;
use app\components\Debug;
use yii\data\ActiveDataProvider;
use app\modules\str\models\Order;

/* @var $this yii\web\View */
/* @var $model app\modules\str\models\Project */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view">

    <p>
        <span class="h3">Проект: <strong><?= Html::encode($this->title) ?></strong></span>
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
                'name',
                'location',
                'date',
            ],
        ]);

        //
        $summ_project = Order::find()->where(['id_project' => $model->id])->sum('summ1');
        if ($summ_project !== null){
            ?>
                <p>Сумма всех объемов работ: <span><strong><?=$summ_project?></strong> &#x20bd</span></p>
            <?php
        }
    ?>

    <p>
        <?= Html::a('Добавить комнату', ['/str/room/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
        $id = intval($model->id);
        $searchModel = new RoomSearch();
        $params = (Yii::$app->request->queryParams); $params['id_parent'] = $id;
        //echo $id;
        $dataProvider = $searchModel->search($params);
        //$dataProvider = Room::find()->where(['id_parent' => $id]); // ;->all();
        //$dataProvider = Room::find()->all();
        //echo Debug::d($dataProvider,'dataProvider'); die;


        //data provider
        $dataProvider = new ActiveDataProvider([
            'query' => Room::find()->where(['id_parent' => $id]),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

    ?>

    <?php
    echo  GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
//            [
//                'label' => 'parent',
//                'attribute' => 'id_parent',
//            ],
            'name',
            'height',
            'perimeter',
            'wall_count',
            's_roof',
            's_floor',

            [
                'attribute' => 'price3',
                'header' => 'Итоговая СУММА',
                'value' => function ($data){
                    $summ = Order::find()->where(['id_room' => $data->id])->sum('summ1');
                    return $summ;
                }
            ],

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
                                'title' => Yii::t('app', 'delete'),
                    ]);
                }

                ],

                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url ='/str/room/view?id='.$model->id;
                        return $url;
                    }

                    if ($action === 'update') {
                        $url ='/str/room/update?id='.$model->id;
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url ='/str/room/delete?id='.$model->id;
                        return $url;
                    }
                }

            ],
        ],
    ]);
    ?>


</div>
