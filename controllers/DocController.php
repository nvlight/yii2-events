<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 24.03.2018
 * Time: 18:43
 */

namespace app\controllers;

use app\models\ChangeFileForm;
use app\models\File;
use Yii;
use app\models\LoadDocForm;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\components\AuthLib;
use yii\helpers\FileHelper;
use app\components\Debug;

class DocController extends Controller
{
    //
    public function actionLoad(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

//        $hash = password_hash('filename', PASSWORD_BCRYPT);
//        $pass = '$2y$10$OKDaILL7inWHCUFr0bukwOgMIg/CCqdtkX5YQ9XHxHGFs.fp4IRBS.jpg';
//        $hash = preg_replace("#[/\\\]#",'',$hash);
//        echo 'hash: ' . $hash;

        $model = new LoadDocForm();
        if (Yii::$app->request->post()){
            $model->file = UploadedFile::getInstance($model,'file');
            if ($model->validate()){

                // предотвращение числа загрузки файлов больше чем лимит
                $userFilesInfo = File::getUserFilesInfo();
                if ($userFilesInfo['count'] >= Yii::$app->params['fileMaxAmount']){
                    Yii::$app->session->setFlash('loadFile','Превышения лимита загружаемых файлов!');
                    $userFiles = File::findAll(['i_user' => $_SESSION['user']['id']]);
                    $this->layout = '_main';
                    return $this->render('load', ['model' => $model, 'userFiles' => $userFiles]);
                }

                // предотвращение объема трафика
                if ( ($userFilesInfo['filesize'] + $model->file->size) > Yii::$app->params['fileMaxSize']){
                    Yii::$app->session->setFlash('loadFile','Превышения объема загружаемых файлов!');
                    $userFiles = File::findAll(['i_user' => $_SESSION['user']['id']]);
                    $this->layout = '_main';
                    return $this->render('load', ['model' => $model, 'userFiles' => $userFiles]);
                }

                // предотвращение дублирования файла по имени - способ потом надо изменить...
//                $search_origin = File::findOne(['i_user' => $_SESSION['user']['id'], 'name' => $model->file->name]);
//                if ($search_origin) {
//                    Yii::$app->session->setFlash('loadFile','Такой файл уже был загружен!');
//                    $userFiles = File::findAll(['i_user' => $_SESSION['user']['id']]);
//                    $this->layout = '_main';
//                    return $this->render('load', ['model' => $model, 'userFiles' => $userFiles]);
//                }

                Yii::$app->session->setFlash('loadFile','Файл загружен');
                $path = Yii::$app->params['pathUploads'];
                $hash = password_hash($model->file, PASSWORD_BCRYPT);
                $hash = preg_replace("#[/\\\]#",'_',$hash) . '.' . $model->file->extension;
                //echo 'hash: ' . $hash;
                if ($model->file){
                    $model->file->saveAs($path . $hash);
                    //
                    $file = new File();
                    $file->i_user = $_SESSION['user']['id'];
                    $file->name = $model->file->name;
                    $file->hash = $hash;
                    $file->filesize = filesize($path . $hash);
                    $filename = htmlentities($model->file->name);
                    if ($file->save()) Yii::$app->session->setFlash('loadFile','Файл \'' . $filename . '\' загружен!');
                }else{
                    Yii::$app->session->setFlash('loadFile','Пустой файл!');
                }
            }
        }
        // получим тут все вайлы текущего пользователя
        $userFiles = File::find()->where(['i_user' => $_SESSION['user']['id']])->all();
        //echo Debug::d($userFiles,'$userFiles');
        $this->layout = '_main';
        return $this->render('load', ['model' => $model, 'userFiles' => $userFiles]);
    }

    //
    public function actionDel(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $del_item = File::findOne(['i_user' => $_SESSION['user']['id'], 'id' => Yii::$app->request->get('id')]);
        $abs_url = Yii::$app->params['pathUploads'] . $del_item->hash;
        if (is_file($abs_url) && unlink($abs_url)){
            if ($del_item->delete()) {
                $filename = htmlentities($del_item->name);
                Yii::$app->session->setFlash('loadFile', 'Файл \'' . $filename .'\' удален!');
            }
        }
        return $this->redirect(['doc/load']);
    }

    //
    public function actionDownload(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $down_item = File::findOne(['i_user' => $_SESSION['user']['id'], 'id' => Yii::$app->request->get('id')]);
        if ($down_item && is_file(Yii::$app->params['pathUploads'] . $down_item->hash)){
            return Yii::$app->response
                ->sendFile(Yii::$app->params['pathUploads'] . $down_item->hash, $down_item->name);
        }
    }

    //
    public function actionShow(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $down_item = File::findOne(['i_user' => $_SESSION['user']['id'], 'id' => Yii::$app->request->get('id')]);
        $filename = Yii::$app->params['pathUploads'] . $down_item->hash;
        if ($down_item && is_file($filename)){
            $mimeType = FileHelper::getMimeType($filename);
            // echo 'mime: ' . $mimeType;
            $white_list = ['image/png', 'image/gif', 'image/jpeg'];
            if (in_array($mimeType, $white_list)){
                return $this->redirect(Url::to('@web/upload/' . $down_item->hash,true));
            }else{
                return $this->redirect(Url::to(['doc/download', 'id' => $down_item->id],true));
            }
        }
    }

    //
    public function actionUpd(){

        if (!Authlib::appIsAuth()) { AuthLib::appGoAuth(); }

        $model = new ChangeFileForm();
        $upd_item = File::findOne(['i_user' => $_SESSION['user']['id'], 'id' => Yii::$app->request->get('id')]);
        if (!$upd_item) die('Vi doigralis!');
        $model->filename = $upd_item->name;
        $model->notice = $upd_item->notice;

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()){
            $upd_item->name = $model->filename;
            $upd_item->notice = $model->notice;
            if ($upd_item->save()) {
                Yii::$app->session->setFlash('changeFile', 'Мета-данные файла сохранены!');
            }
        }

        $this->layout = '_main';
        return $this->render('upd', ['upd_item' => $upd_item, 'model' => $model]);
    }

}