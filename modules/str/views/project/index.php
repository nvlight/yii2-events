<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\str\models\Room;
use app\modules\str\models\RoomSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\str\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Проекты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <p><span class="h3"><?= Html::encode($this->title) ?></span></p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать проект', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'content' => function($model, $key, $index, $column){
                    return $model->id;
                }
            ],
            [
                //'header' => 'Rank',
                'attribute' => 'id',
                'label' => 'id',
                'value' => function($data){
                    return $data->id;
                }
            ],
            'name',
            'location',
            'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
