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

class VideoController extends Controller
{
    public function actionIndex(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }
        $video = new Video();
        //
        if (Yii::$app->request->isPost && $video->load(Yii::$app->request->post())){
            $video->i_user = $_SESSION['user']['id'];
            if ($video->validate()){
                $video->dt_publish = Yii::$app->formatter->asTime($video->dt_publish, 'yyyy-MM-dd');
                if ($video->save()){
                    Yii::$app->session->setFlash('addVideo','Видео добавлено');
                    return $this->redirect('index');
                }
            }
        }
        $all = Video::find(['i_user' => $_SESSION['user']['id']])->with('categoryvideo')->all();
        $this->layout = '_main';
        return $this->render('index', ['model' => $video, 'all' => $all]);
    }

    public function actionTestapi(){

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
            'maxResults' => 7,
        ));

        //echo Debug::d($rs2,'youtube result');
        $this->layout = '_main';
        return $this->render('main',['data' => $rs ]);
    }


    public function actionTestapi2(){

        // используется вариант с CURL

        $video_id = 'wHObvCfiUyI';
        $api_key = Yii::$app->params['youtube_api_key_2'];

        $url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet,contentDetails,statistics,status";
        $url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet,statistics";
        $url = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet,contentDetails,statistics&fields=items(id,contentDetails,etag,snippet(publishedAt,title,description,thumbnails(medium),channelTitle,localized),statistics)";
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
        $this->layout = '_main';
        return $this->render('main',['data' => $jsonData ]);
    }

    public function actionTestapi3(){

        // используется вариант с fOpen

        $video_id = 'wHObvCfiUyI';
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

}