<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 20.01.2018
 * Time: 18:39
 */

use yii\widgets\ActiveForm;
use app\models\RestoreForm;
use yii\helpers\Html;
use app\components\Debug;
use yii\helpers\Url;

$this->title = 'Events | Восстановление пароля - пароль сброшен';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page restore'], 'keywords');

?>

<section class="main-auth">
    <div class="section-table">
        <div class="section-row">
            <div class="section-cell">
                <div class="form-auth">
                    <h2><i class="fa fa-sun-o" aria-hidden="true"></i> Events</h2>
                    <hr class="hr-toh2">
                    <p class="capt show">Восстановление пароля</p>
                    <?php
                        if ($err_msg === ''):
                            ?>
                                <p>Пароль для пользователя <?=html::encode($uname)?> был сброшен</p>
                                <p>Новый пароль: <?=html::encode($np)?></p>
                            <?php
                        else:
                            ?>
                                <p><?=Html::encode($err_msg)?></p>
                                <p>Обратитесь в службу поддержки</p>
                            <?php
                        endif;
                    ?>
                    <?= Html::a('На главную!', ['site/login', ], ['class' => 'btn btn-success']) ?>
                </div>

            </div>
        </div>
    </div>
</section>
