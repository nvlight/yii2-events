<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 01.04.2018
 * Time: 22:17
 */

use app\components\Debug;
use yii\data\ArrayDataProvider;
use app\models\Video;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use nerburish\matchheight\MatchHeightAsset;
use nerburish\matchheight\MatchHeightView;

//$data = [
//    ['id' => 1, 'name' => 'name 1'],
//    ['id' => 2, 'name' => 'name 2'],
//    ['id' => 100, 'name' => 'name 100'],
//];
//
//$provider = new ArrayDataProvider([
//    'allModels' => $data,
//    'pagination' => [
//        'pageSize' => 10,
//    ],
//    'sort' => [
//        'attributes' => ['id', 'name'],
//    ],
//]);


$query = Video::find()->where(['i_user' => $_SESSION['user']['id']]);

$provider = new ActiveDataProvider([
    'query' => $query,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'defaultOrder' => [
            'id' => SORT_DESC,
            'i_cat' => SORT_ASC,
        ]
    ],
]);

// получает строки для текущей запрошенной странице
$rows = $provider->getModels();

//echo Debug::d($rows,'rows');

//echo yii\grid\GridView::widget([
//    'dataProvider' => $provider,
//]);

echo MatchHeightView::widget([
	'dataProvider' => $provider,
	'itemView' => '_item',
//	'cssFile' => [
//		"@web/css/grid-demo.css"
//	]
]);

?>

<div>

    <div class="col-md-3 item">
        <p><strong>Video Id: </strong>mhaxD2a4AnQ</p>
        <p><strong>title: </strong>AliExpress HUANAN X79 Motherboards - Are they a LEGIT OPTION for PC Builders!?</p>
        <p><strong>description: </strong>You guys have been spamming me about getting this video done, so it got put right to the front of the queue and here it is. X79, $110 motherboard, and an e5-1650 (similar to a i7-3930K except...</p>
        <p><strong>publishedAt: </strong>2018-03-03T07:00:03.000Z</p>
        <img src=" " alt="">

    </div>
    <div class="col-md-3 item">
        <p><strong>Video Id: </strong>IEgs61tFHtc</p>
        <p><strong>title: </strong>NEW Chinese Huanan X79 Motherboard Unboxing and First Impressions</p>
        <p><strong>description: </strong>The price of socket 2011 x79 xeons is very cheap right now. If you're lucky you can pick up a 6 core 12 thread xeon for around 25-50 dollars which is pretty crazy. Thats all well and good...</p>
        <p><strong>publishedAt: </strong>2018-03-12T06:18:39.000Z</p>
        <img src=" " alt="">

    </div>
    <div class="col-md-3 item">
        <p><strong>Video Id: </strong>hx9389UmTvQ</p>
        <p><strong>title: </strong>Обзор Huanan x79 Gaming Deluxe</p>
        <p><strong>description: </strong>Обзор Huanan x79 Gaming Deluxe https://cloud.mail.ru/public/K6jr/tWNsDUcZi - отчет аиды по Huanan x79 GE http://got.by/1zmsoh - купить можно тут https://goo.gl/F4RkCc...</p>
        <p><strong>publishedAt: </strong>2017-11-06T12:34:58.000Z</p>
        <img src=" " alt="">

    </div>

    <div class="col-md-3 item">
        <p><strong>Video Id: </strong>pg739TdPFeA</p>
        <p><strong>title: </strong>Chinese x79 Motherboards... Do they even work???</p>
        <p><strong>description: </strong>I'm now on Patreon! Working on putting together projects, merch, rewards and content for Patreon backers. https://patreon.com/CraftComputing?utm_medium=social&amp;utm_source=twitter&amp;utm_campaign=cre...</p>
        <p><strong>publishedAt: </strong>2017-07-11T06:20:05.000Z</p>
        <img src=" " alt="">

    </div>
    <div class="col-md-3 item">
        <p><strong>Video Id: </strong>MUsvSorh-2c</p>
        <p><strong>title: </strong>Huanan x79 v 2.47 + Xeon e5-2670 + 16Gb Sk Hunix + GTX 1050TI 4Gb - Мой новый компьютер из Китая</p>
        <p><strong>description: </strong>Материнская плата: https://goo.gl/s3IZSN ✓ Процессор: https://goo.gl/gnyeKw или https://goo.gl/dLdezV а также геймерский цпу https://goo.gl/v6QD...</p>
        <p><strong>publishedAt: </strong>2017-07-31T09:41:10.000Z</p>
        <img src=" " alt="">

    </div>
    <div class="col-md-3 item">
        <p><strong>Video Id: </strong>nLcYlzeqOss</p>
        <p><strong>title: </strong>Материнская Плата Huanan x79 (2.46) сокет 2011 + ЦП Intel Xeon E5 2680 + ОЗУ DDR3 16Гб | BIOS</p>
        <p><strong>description: </strong>Китайская материнская плата на LGA2011 Huanan x79 2.46 + серверный процессор Intel Xeon E5-2680 + оперативка DDR3 16Гб. ECC REG. Компл...</p>
        <p><strong>publishedAt: </strong>2017-04-08T18:04:15.000Z</p>
        <img src=" " alt="">

    </div>

    <div class="col-md-3 item">
        <p><strong>Video Id: </strong>7Tj1Vq4ZrF0</p>
        <p><strong>title: </strong>Вся правда о китайцах Lga2011  Материнские платы из Китая Huanan x79</p>
        <p><strong>description: </strong>Вся правда о китайцах Lga2011 Материнские платы из Китая https://vk.com/topic-114738215_34562844 - Железки китайского производс...</p>
        <p><strong>publishedAt: </strong>2017-02-15T08:21:42.000Z</p>
        <img src=" " alt="">

    </div>

</div>