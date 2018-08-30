<?php

namespace app\modules\str\models;

use Yii;

/**
 * This is the model class for table "str_order".
 *
 * @property int $id
 * @property int $id_room
 * @property int $id_project
 * @property int $id_job
 * @property double $volume
 * @property double $price1
 * @property double $price2
 * @property double $price3
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'str_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_job', 'volume', ], 'required'],
            [['id_room', 'id_project', 'id_job', 'volume', 'price1', 'price2', 'price3'], 'integer'],
            [['volume', 'price1', 'price2', 'price3'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_room' => 'Комнаты',
            'id_project' => 'Проекта',
            'id_job' => '№ работы',
            'volume' => 'Объем',
            'price1' => 'Цена (номинал)',
            'price2' => 'Цена (норм)',
            'price3' => 'Цена (скидка)',
            'summ1' => 'Сумма (номинал)',
            'summ2' => 'Сумма (скидка)',
        ];
    }

    //
    public function getProject(){
        return $this->hasOne(Project::className(), ['id' => 'id_project']);
    }

    //
    public function getRoom(){
        return $this->hasOne(Room::className(), ['id' => 'id_room']);
    }

    //
    public function getJob(){
        return $this->hasOne(Jobtypes::className(), ['id' => 'id_job']);
    }

}
