<?php

namespace app\modules\str\models;

use Yii;

/**
 * This is the model class for table "str_job_types".
 *
 * @property int $id
 * @property string $name
 * @property double $price1
 * @property double $price2
 * @property double $price3
 * @property string $measure
 * @property string $note
 */
class Jobtypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'str_job_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['price1', 'price2', 'price3'], 'number'],
            [['name', 'note'], 'string', 'max' => 55],
            [['measure'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price1' => 'Price1',
            'price2' => 'Price2',
            'price3' => 'Price3',
            'measure' => 'Measure',
            'note' => 'Note',
        ];
    }
}
