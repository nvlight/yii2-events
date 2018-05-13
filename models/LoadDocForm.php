<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 24.03.2018
 * Time: 18:45
 */

namespace app\models;

use Yii;
use yii\base\Model;

class LoadDocForm extends Model
{
    public $file;

    public function rules(){
        return [
            [ ['file'], 'file',
              'extensions' => 'png, jpg, jpeg, gif, docx, xlsx, pdf, djvu, rar, zip, 7z,txt',
              'maxSize' => Yii::$app->params['fileMaxSize'],
              //'skipOnEmpty' => false, // пропускать, если файл 0 размера
              'maxFiles' => 5,
              //'tooLarge'=>'File has to be smaller than 50MB'
            ],
            [ ['file'], 'required' ],
        ];
    }

    //
    public function labels(){
        return [
            'file' => 'имя файла',
        ];
    }

    //
    public function upload()
    {
        if ($this->validate()){
            // предотвращение числа загрузки файлов больше чем лимит
            $userFilesInfo = File::getUserFilesInfo();
            if ($userFilesInfo['count'] >= Yii::$app->params['fileMaxAmount']){
                Yii::$app->session->setFlash('loadFile','Превышения лимита загружаемых файлов!');
                return;
            }

            // предотвращение объема трафика
            $model_file_sizes = 0;
            foreach($this->file as $file) { $model_file_sizes += $file->size; }
            if ( ($userFilesInfo['filesize'] + $model_file_sizes) > Yii::$app->params['fileMaxSize']){
                Yii::$app->session->setFlash('loadFile','Превышения объема загружаемых файлов!');
                return;
            }

            $saved_files = '';
            foreach($this->file as $file) {
                $path = Yii::$app->params['pathUploads'];
                $hash = password_hash($file, PASSWORD_BCRYPT);
                $hash = preg_replace("#[/\\\]#", '_', $hash) . '.' . $file->extension;
//                $hash = password_hash('filename', PASSWORD_BCRYPT);
//                $pass = '$2y$10$OKDaILL7inWHCUFr0bukwOgMIg/CCqdtkX5YQ9XHxHGFs.fp4IRBS.jpg';
//                $hash = preg_replace("#[/\\\]#",'',$hash);
//                echo 'hash: ' . $hash;

                // т.к. файлов несколько, сохраним их все
                if ($file) {
                    $file->saveAs($path . $hash);
                    // также сохраним это в базе данных
                    $file2 = new File();
                    $file2->i_user = $_SESSION['user']['id'];
                    $file2->name = $file->name;
                    $file2->hash = $hash;
                    $file2->filesize = filesize($path . $hash);
                    $filename = htmlentities($file->name);
                    if ($file2->save()) {
                        $saved_files .= 'Файл \'' . $filename . '\' загружен! ' . "<br/>";
                    }else {
                        $saved_files .= 'Файл \'' . $filename . '\' не загружен загружен! ' . "<br/>";
                    }
                }
            }
            Yii::$app->session->setFlash('loadFile', $saved_files);
        }
    }

    //
    public function upload2()
    {
        if ($this->validate()) {
            foreach ($this->file as $file){
                $file->saveAs('upload/' . $file->imageFile->baseName . '.' . $file->imageFile->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}