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

class ChangeFileForm extends Model
{
    public $filename;
    public $notice;

    public function rules(){
        return [
          [['filename'], 'required'],
            [['filename','notice'], 'string', 'max' => 111 ]
        ];
    }

    public function labels(){
        return [
            'filename' => 'имя файла',
            'notice' => 'примечание',
        ];
    }

}