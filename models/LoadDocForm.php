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
          [['file'], 'file',
              'extensions' => 'png, jpg, jpeg, docx, xlsx, pdf, djvu, rar, zip, 7zip',
              'maxSize' => Yii::$app->params['fileMaxSize'],
              //'tooLarge'=>'File has to be smaller than 50MB'
              ],
            [ ['file'], 'required' ],
        ];
    }

    public function labels(){
        return [
            'file' => 'имя файла',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('upload/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}