<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 27.05.2018
 * Time: 23:13
 */

use app\models\Event;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\widgets\ListView;

$this->title = 'Events | История (new)';
$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page history (new)'], 'keywords');


?>


<div class="page-content">

    <div class="row">
        <div class="col-md-12">
            <?php
            $dataProvider = new ActiveDataProvider([
                'query' => Event::find()->with('category')->with('types'),
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'dtr' => SORT_DESC
                    ]
                ]
            ]);
//                                echo ListView::widget([
//                                    'dataProvider' => $dataProvider,
//                                    'itemView' => '_form',
//                                ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => '#',
                        'attribute' => 'id',
                    ],
                    [
                        'label' => 'Категория',
                        'attribute' => 'i_cat',
                        'value' => function ($data) {
                            return $data->category->name;
                        },
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
                        'label' => 'Дата',
                        'attribute' => 'dtr',
                    ],
                    [
                        'label' => 'Тип',
                        'attribute' => 'type',
                        'value' => function ($data) {
                            return $data->types->name;
                        },
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
