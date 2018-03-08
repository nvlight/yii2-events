<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\data\Pagination;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property integer $i_user
 * @property integer $i_cat
 * @property string $desc
 * @property string $dt
 * @property integer $summ
 * @property integer $type
 * @property string $note
 */
class Event extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'i_cat']);
            //->viaTable('art_tag', ['tag_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function getTypes() {
        return $this->hasOne(Type::className(), ['id' => 'type']);
        //->viaTable('art_tag', ['tag_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function getHistory($sortcol='dtr',$sort='desc') {
        switch ($sortcol){
            case 'id'    : $sortcol = 'id'; break;
            case 'i_cat' : $sortcol = 'i_cat'; break;
            case 'desc'  : $sortcol = 'desc'; break;
            case 'summ'  : $sortcol = 'summ'; break;
            case 'dtr'   : $sortcol = 'dtr'; break;
            case 'type'  : $sortcol = 'type'; break;
            default: { echo 'vi doigralis!'; die;  }
        }
        switch ($sort){
            case 'desc': { $sort = 'asc';  $rsort2 = [$sortcol => SORT_DESC, 'id' => SORT_DESC]; break; }
            default:     { $sort = 'desc'; $rsort2 = [$sortcol =>  SORT_ASC, 'id' => SORT_DESC]; }
        }
        $query = Event::find()->where(['i_user' => $_SESSION['user']['id']])
            ->with('category')
            ->with('types')
            ->orderBy($rsort2)
            //->asArray()
            //->all();
        ;
        //echo Debug::d($query,'query'); die;
        $q_counts = Yii::$app->params['history_post_count'];
        $pages = new Pagination(['totalCount' => $query->count(),'pageSize' => $q_counts,
            'pageSizeParam' => false, 'forcePageParam' => false]);
        //echo Debug::d($pages,'pages'.$pages->offset); die;
        $events = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        $ev2 = Event::find()->where(['i_user' => $_SESSION['user']['id'], ])->with('category');
        // events','pages','sort','ev2'
        return [$events, $pages, $sort, $ev2];
    }

    /*
     *
     *
     * */
    public static function getEventRowsStrByArray($id,$desc,$summ,$dt,$cl1,$cl2,$cat_name){
        $dt = Yii::$app->formatter->asTime($dt, 'dd.MM.yyyy');
        $trh = <<<TRH
<tr class="actionId_{$id}">
                                <td>{$id}</td>
<td class='item_cat'>{$cat_name}</td>
<td class='item_desc'>{$desc}</td>
<td class='item_summ'>{$summ}</td>
<td class='item_dtr'>{$dt}</td>
<td class='item_type'><span style="background-color: #{$cl2}" class="dg_type_style">{$cl1}</span></td>
<td>
                                    <span class="btn-action" title="Просмотр">
                                        <a class="evActionView"                                           
                                           data-id="{$id}" href="#"
                                        >
                                            <span class="glyphicon glyphicon-eye-open" ></span>
                                        </a>
                                    </span>
    <span class="btn-action" title="Редактировать">                                     
                                        <a class="evActionUpdate"                                          
                                            data-id="{$id}" href="#"
                                        >
                                            <span class="glyphicon glyphicon-pencil" >
                                            </span>
                                        </a>
                                    </span>
    <span class="btn-action" title="Удалить">
                                        <a class="evActionDelete"
                                           data-id="{$id}" href="#"
                                        >
                                            <span class="glyphicon glyphicon-trash" >
                                            </span>
                                        </a>
                                    </span>
</td>
</tr>
TRH;
        return $trh;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['i_user', 'i_cat', 'desc', 'dtr','type','summ'], 'required'],
            [['i_user', 'i_cat', 'summ', 'type'], 'integer'],
            [['dt'], 'safe'],
            [['dtr'], 'string', 'length' => [8]],
            [['desc'], 'string', 'max' => 101],
            [['note'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'i_user' => 'Пользователь',
            'i_cat' => 'Категория',
            'desc' => 'Описание',
            'dt' => 'Дата системная',
            'summ' => 'Сумма',
            'dtr' => 'Дата',
            'type' => 'Тип',
            'note' => 'Замечание',
        ];
    }
}
