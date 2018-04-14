<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 29.03.2018
 * Time: 17:21
 */

namespace app\controllers;


use app\components\Debug;
use app\models\Video;
use yii\web\Controller;
use app\components\AuthLib;
use Yii;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_Books;
use DateInterval;
use app\models\VideoSearch;
use app\models\VideoSearch2;

class VideoController extends Controller
{
    //
    public static function actionYoutubeFindVideoById($id){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        // используем youyube Api, чтобы узнать, есть ли видео с таким ИД
        // если есть, то немножко форматируем его, используя параметры
        // &part=snippet,contentDetails,statistics&fields=items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(medium),channelTitle,localized),statistics)
        $video_id = $id;
        $api_key = Yii::$app->params['youtube_api_key_2'];
        //$url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet,contentDetails,statistics&fields=items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(medium),channelId,channelTitle,localized),statistics)";

        $params = array(
            'part' => 'contentDetails',
            'id' => $video_id,
            'key' => $api_key,
            'part' => 'snippet,contentDetails,statistics',
            'fields' => 'items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(default,medium,high),channelTitle,channelId,localized),statistics)'
        );
        $url = 'https://www.googleapis.com/youtube/v3/videos?' . http_build_query($params);

        $curlSession = curl_init();

        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $jsonData = json_decode(curl_exec($curlSession));
        curl_close($curlSession);
        //echo Debug::d($jsonData,'json_dt',1);
        //echo 'count: ' . count($jsonData->items);

        if (count($jsonData->items) == 1){
            return $jsonData->items[0];
        }
        return false;
    }

    //
    public static function actionYoutubeParseUrl($url){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        // есть 2 вида ссылок
        // 'https://www.youtube.com/watch?v=ngI1CVGkWoY' и 'https://youtu.be/ngI1CVGkWoY'
        // нужно их определить и вытащить параметр v для первого и остаток после / во втором
        // после этого, нужно полученный ИД проверить на существование, и это мы сделаем с помощью Ютуб АПИ
        $link = 'https://www.youtube.com/watch?v=ngI1CVGkWoY';
        $link = 'https://youtu.be/ngI1CVGkWoY';

        $link = $url;
        $fl = 0; $subs = '';
        $youtubePattern_1 = 'https://www.youtube.com/';
        $youtubePattern_2 = 'https://youtu.be/';
        if (mb_strpos($link, $youtubePattern_1) !== false){
            if (mb_strlen($link) > $youtubePattern_1){
                $subs0 = (mb_substr($link,24));
                //echo Debug::d($subs0);
                if (mb_strpos($subs0, '=')){
                    $subs1 = explode('=', $subs0);
                    //echo Debug::d($subs1);
                    if (mb_strlen($subs1[1]) > 0)
                        $subs = $subs1[1];
                }
            }
        }elseif(mb_strpos($link, $youtubePattern_2) !== false){
            if (mb_strlen($link) > $youtubePattern_2)
                $subs = (mb_substr($link,17));
        }
        // вывод текущего разобранного ИД
        //echo 'subs: ' . $subs;

        return $subs !== '' ? $subs : false;
    }

    //
    public function actionIndex(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        $video = new Video();
        //
        if (Yii::$app->request->isPost && $video->load(Yii::$app->request->post())){
            $video->i_user = $_SESSION['user']['id'];

            $res = self::actionYoutubeParseUrl($video->url);
            if ($res !== false){
                // ищем видео с таким ид
                $res2 = self::actionYoutubeFindVideoById($res);
                if ($res2 !== false){
                    //echo Debug::d($res2, 'res2',1,1);
                    $video->video_id = $res2->id;
                    $video->dt_publish = new \DateTime($res2->snippet->publishedAt); $video->dt_publish = $video->dt_publish->format('Y-m-d');
                    $video->title = $res2->snippet->title;
                    $video->description = $res2->snippet->description;
                    $time = $res2->contentDetails->duration;
                    $video->duration = new DateInterval($time); $video->duration = $video->duration->format('%H:%I:%S');
                    $video->viewcount = $res2->statistics->viewCount;
                    $video->channeltitle = $res2->snippet->channelTitle;
                    $video->channelid = $res2->snippet->channelId;

                    // save thumbnails
                    $opts = array('http' =>
                        array(
                            'method' => 'GET',
                            'max_redirects' => '0',
                            'ignore_errors' => '1',
                        )
                    , 'ssl' => array(
                            'verify_peer' => true,
                            'cafile' => '/SRV/php721/extras/ssl/' . "cacert.pem",
                            'ciphers' => 'HIGH:TLSv1.2:TLSv1.1:TLSv1.0:!SSLv3:!SSLv2',
                            'disable_compression' => true,
                        )
                    );
                    $context = stream_context_create($opts);
                    $thumbnails = $res2->snippet->thumbnails;
                    $thumbs = ['default','medium','high']; $thumbnails_json = '{}'; $thumbnails_arr = [];
                    foreach ($thumbs as $k => $v){
                        $explode = explode('/',$thumbnails->$v->url);
                        $filename = $explode[count($explode)-2];
                        $filequality = $explode[count($explode)-1];
                        $fgc = file_get_contents($thumbnails->$v->url,false,$context);
                        if ($fgc !== false) {
                            $thumbnails_arr[] = $filename . '$' . $filequality;
                            file_put_contents( Yii::$app->params['youytube_pathUploads'] . $filename . '$' . $filequality,$fgc);
                        }
                    }
                    $thumbnails_json = json_encode($thumbnails_arr);
                    $video->thumbnails = $thumbnails_json;
                }
            }

            $video->dt_publish = Yii::$app->formatter->asTime($video->dt_publish, 'yyyy-MM-dd');

            if ($video->validate()){
                if ($video->save()){
                    Yii::$app->session->setFlash('addVideo','Видео добавлено');
                    return $this->redirect('index');
                }
            }
            //echo Debug::d($video->errors,'error',2,1);
        }
        $all = Video::find()->where(['active' => '1', 'i_user' => $_SESSION['user']['id']])
            ->with('categoryvideo')->all();
        //echo Debug::d(count($all),'count rows',1,0);
        //echo Debug::d($all,'all_videos',1,2);
        $this->layout = '_main';
        return $this->render('index', ['model' => $video, 'all' => $all]);
    }

    //
    public function actionTestapi2($id=''){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        // используется вариант с CURL
        $video_id = 'wHObvCfiUyI';
        if ($id !== ''){
            $video_id = $id;
        }

        $api_key = Yii::$app->params['youtube_api_key_2'];

        $params = array(
            'part' => 'contentDetails',
            'id' => $video_id,
            'key' => $api_key,
            'part' => 'snippet,contentDetails,statistics',
            'fields' => 'items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(medium),channelTitle,localized),statistics)'
            //&part=snippet,contentDetails,statistics&fields=items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(medium),channelTitle,localized),statistics)
        );

        $url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet,contentDetails,statistics,status";
        $url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet,statistics";
        $url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet,contentDetails,statistics&fields=items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(medium),channelTitle,localized),statistics)";
        $url = 'https://www.googleapis.com/youtube/v3/videos?' . http_build_query($params);
        //$url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
        //$url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet,statistics&fields=items(id,snippet(channelId,title,categoryId),statistics)";
        // "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=$video_id&key=$api_key"
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $jsonData = json_decode(curl_exec($curlSession));
        curl_close($curlSession);
        //echo Debug::d($jsonData);

        //
        $video_id = $jsonData->items[0]->id;
        $dt = new \DateTime($jsonData->items[0]->snippet->publishedAt); $dt_new = $dt->format('Y-m-d');
        $title = $jsonData->items[0]->snippet->title;
        $description = $jsonData->items[0]->snippet->description;
        $time = $jsonData->items[0]->contentDetails->duration;
        $duration = new DateInterval($time); $duration_new = $duration->format('%H:%I:%S');
        //
        $channelTitle = $jsonData->items[0]->snippet->channelTitle;
        $viewCount = $jsonData->items[0]->statistics->viewCount;

        echo '<strong>id: </strong>' . $video_id; echo "<br>";
        echo '<strong>publishedAt: </strong>' . $dt_new; echo "<br>";
        echo '<strong>title: </strong>' . $title; echo "<br>";
        echo '<strong>description: </strong>' . $description;  echo "<br>";
        echo  '<strong>duration: </strong>' . $duration_new; echo "<br>";
        echo  '<strong>channelTitle: </strong>' . $channelTitle; echo "<br>";
        echo  '<strong>viewCount: </strong>' . $viewCount; echo "<br>";
        die;

        $this->layout = '_main';
        return $this->render('main',['data' => $jsonData ]);
    }

    //
    public function actionTestapi3($id=''){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        // используется вариант с fOpen
        $video_id = 'wHObvCfiUyI';
        if ($id !== ''){
            $video_id = $id;
        }

        $api_key = Yii::$app->params['youtube_api_key_1'];

        $url = 'https://www.googleapis.com/youtube/v3/videos';
        $cn_match = 'www.googleapis.com';
        // part=snippet,contentDetails,statistics&fields=items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(medium),channelTitle,localized),statistics)";
        $data = array (
            'key' => $api_key,
            //'part' => 'snippet',
            'part' => 'snippet,contentDetails,statistics',
            'fields' => 'items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(medium),channelTitle,localized),statistics)',
            'id' => $video_id
        );

        $scu = $url . '?' . http_build_query($data);
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'max_redirects' => '0',
                'ignore_errors' => '1',
            )
        , 'ssl' => array(
                'verify_peer' => true,
                'cafile' => '/SRV/php721/extras/ssl/' . "cacert.pem",
                'ciphers' => 'HIGH:TLSv1.2:TLSv1.1:TLSv1.0:!SSLv3:!SSLv2',
                'CN_match' => $cn_match,
                'disable_compression' => true,
            )
        );

        $context = stream_context_create($opts);
        $stream = fopen($scu, 'r', false, $context);

        // информация о заголовках, а также
        // метаданные о потоке
        //echo Debug::d(stream_get_meta_data($stream),'stream_get_meta_data($stream)');

        // актуальная информация по ссылке $url
        //echo Debug::d(stream_get_contents($stream),'stream_get_contents($stream)');
        $response = json_decode(stream_get_contents($stream));
        fclose($stream);
        //$response = file_get_contents($scu);
        //echo Debug::d($context);
        //echo $scu;
        //echo Debug::d($response);
        $this->layout = '_main';
        return $this->render('main',['data' => $response ]);
    }

    //
    public function actionTestapi($id='')
    {
        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        // используется вариант с fileGetContents
        $video_id = '83OavSpuXXY';
        if ($id !== ''){
            $video_id = $id;
        }

        $api_key = Yii::$app->params['youtube_api_key_1'];
        $params = array(
            'part' => 'contentDetails',
            'id' => $video_id,
            'key' => $api_key,
            'part' => 'snippet,contentDetails,statistics',
            'fields' => 'items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(default,medium,high),channelTitle,localized),statistics)'
        );
        $url = 'https://www.googleapis.com/youtube/v3/videos?' . http_build_query($params);

        $opts = array('http' =>
            array(
                'method' => 'GET',
                'max_redirects' => '0',
                'ignore_errors' => '1',
            )
        , 'ssl' => array(
                'verify_peer' => true,
                'cafile' => '/SRV/php721/extras/ssl/' . "cacert.pem",
                'ciphers' => 'HIGH:TLSv1.2:TLSv1.1:TLSv1.0:!SSLv3:!SSLv2',
                //'CN_match' => $cn_match,
                'disable_compression' => true,
            )
        );

        $context = stream_context_create($opts);
        $json_result = file_get_contents ($url,false ,$context);
        $json_decode = json_decode($json_result)->items[0];
        //echo Debug::d($json_decode,'json_result',1,1);
        $thumbnails = $json_decode->snippet->thumbnails;
        echo Debug::d( $thumbnails,'$thumbnails',1,0);
        $thumbs = ['default','medium','high'];
        foreach ($thumbs as $k => $v){
            $explode = explode('/',$thumbnails->$v->url);
            $filename = $explode[count($explode)-2];
            $filequality = $explode[count($explode)-1];
            $fgc = file_get_contents($thumbnails->$v->url,false,$context);
            file_put_contents( Yii::$app->params['youytube_pathUploads'] . $filename . '$' . $filequality,$fgc);
        }
        //echo Debug::d( $thumbs_array,'$thumbs_array',1,1);
        // convert to array
    }

    //
    public function actionWatch($id=false){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $video = Video::findOne($id);
        if ($video) $id = $video->video_id;
        //echo Debug::d($video,'video');
        $this->layout = '_main';
        return $this->render('watch',[$id => $id]);
    }

    //
    public function actionWatchYt($id=false){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $this->layout = '_main';
        return $this->render('watchyt',['id' => $id]);
    }

    //
    public function actionGetYtVideo($id=''){

        if (!Authlib::appIsAuth()) {
            echo json_decode(['success' => 'no', 'message' => 'auth is required']); die;
        }

        if (Yii::$app->request->isAjax){
            $video = Video::findOne($id);
            if ($video){
                $iframe = <<<IFRAME
<iframe width="560" height="315" src="https://www.youtube.com/embed/{$video->video_id}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
IFRAME;
                $rs = ['success' => 'yes', 'message' => 'video is finded', 'iframe' => $iframe ];
            }else{
                $rs = ['success' => 'no', 'message' => 'video is NOT finded', ];
            }
            die(json_encode($rs));
        }
    }

    //
    public function actionGetYtVideoByHash($id=''){

        if (!Authlib::appIsAuth()) {
            echo json_decode(['success' => 'no', 'message' => 'auth is required']); die;
        }

        if (Yii::$app->request->isAjax){
            $iframe = <<<IFRAME
<iframe width="560" height="315" src="https://www.youtube.com/embed/{$id}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
IFRAME;
            $rs = ['success' => 'yes', 'message' => 'video is finded', 'iframe' => $iframe ];
            die(json_encode($rs));
        }
    }

    //
    public function actionMaxheight(){

        $ids[] = 'N584L3HdLfg';
        $api_key = Yii::$app->params['youtube_api_key_1'];

        $client = new Google_Client();
        $client->setDeveloperKey($api_key);
        $youtube = new Google_Service_YouTube($client);

        //$rs = $youtube->videos->listVideos('snippet, statistics, contentDetails', [
        //    'id' => $ids,
        //]);
        $rs = $youtube->search->listSearch('id,snippet', array(
            'q' => 'x79 huanan',
            'maxResults' => 3,
        ));

        $this->layout = '_main';
        return $this->render('testmaxheight',['rs' => $rs ]);
    }

    //
    public function actionSearch(){

        $searchModel = new VideoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = '_main';
        return $this->render('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    //
    public function actionSearch2(){

        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //echo Debug::d($searchModel,'searchModel',1,2);

        $rs = false; $model = new VideoSearch2();
        if (Yii::$app->request->isPost){
            //echo Debug::d($searchModel,'searchModel');
            echo Debug::d($_REQUEST,'request');
            $nkey = 'VideoSearch2';
            if (array_key_exists($nkey,$_REQUEST) && is_array($_REQUEST[$nkey]) && count($_REQUEST[$nkey])){
                //$a = Yii::$app->request->post(['Video']);
                $a = $_POST[$nkey];
                $searchModel = Video::find()
                    ->where(['i_user' => $_SESSION['user']['id'], 'active' => '1']);
                //
                $model->i_cat = 0;
                if (array_key_exists('i_cat',$a) && $a['i_cat'] !== '0' ){
                    $searchModel = $searchModel->andWhere(['like', 'i_cat',    $a['i_cat'] ]);
                    $model->i_cat = $a['i_cat'];
                }
                if (array_key_exists('title',$a)){
                    $model->title = $a['title'];
                    $searchModel = $searchModel->andWhere(['like', 'title',    $a['title'] ]);
                }
                if (array_key_exists('duration',$a)){
                    $model->duration = $a['duration'];
                    $searchModel = $searchModel->andWhere(['like', 'duration', $a['duration'] ]);
                }
                //echo Debug::d($searchModel,'$searchModel');
                //$searchModel = $searchModel->all();
                $searchModel = $searchModel->asArray()->all();//->count();
                $rs = $searchModel;
                //echo Debug::d($searchModel,'$searchModel',1,1);
            }
        }

        $this->layout = '_main';
        return $this->render('search2', [
            'model' => $model, 'rs' => $rs
        ]);

    }

    //
    // Search in YOUTUBE API
    //
    public function actionYtSearch1(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        // используется вариант с самим объектом youyube -> search -> listSearch

        $ids[] = 'N584L3HdLfg';
        $api_key = Yii::$app->params['youtube_api_key_1'];

        $client = new Google_Client();
        $client->setDeveloperKey($api_key);
        $youtube = new Google_Service_YouTube($client);

        //$rs = $youtube->videos->listVideos('snippet, statistics, contentDetails', [
        //    'id' => $ids,
        //]);
        $rs = $youtube->search->listSearch('id,snippet', array(
            'q' => 'x79 huanan',
            'maxResults' => 3,
            'videoDuration' => 'medium',
            'type' => 'video',

        ));

        //echo Debug::d($rs,'youtube result');
        $this->layout = '_main';
        return $this->render('ytsearch1',['rs' => $rs ]);
    }



}