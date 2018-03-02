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
use yii\captcha\Captcha;


$this->title = 'Events | Восстановление пароля';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page restore'], 'keywords');

//$_SERVER['REQUEST_METHOD'] === 'POST'

?>

<section class="main-auth">
    <div class="section-table">
        <div class="section-row">
            <div class="section-cell">
                <?php
                    if (1==1) :
                        $form = ActiveForm::begin(
                            [
                                'action' => ['user/restore'],
                                'method' => 'post',
                                'options'  => [
                                    'class' => 'form-auth',
                                ]
                            ]
                        );
                    else:
                    endif;
                ?>
                <h2><i class="fa fa-sun-o" aria-hidden="true"></i> Events</h2>
                <hr class="hr-toh2">
                <p class="capt show">Восстановление пароля</p><?php

                if (Yii::$app->session->hasFlash('restore')):
                    ?>
                    <p class="capt show" style="font-size: 12px; color: #DF4326;">
                        <?=Html::encode(Yii::$app->session->getFlash('restore'))?>
                    </p>
                <?php
                endif;

                if (!$isRestore) :
                    echo $form->field($model, 'email',
                        ['inputOptions' => [
                            'placeholder' => 'Введите email',
                            'class' => 'form-control'
                        ]
                        ])->textInput(['autofocus' => true]);

                    echo $form->field($model, 'verifyCode')->widget(
                        Captcha::className(),
                        [
                            'captchaAction' => 'user/captcha',
                            //'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                            'options' => [
                                'placeholder' => 'Введите капчу',
                                'class' => 'form-control',
                            ]
                        ]
                    )->label('Капча');


                    echo Html::submitButton('Восстановить', ['class' => 'btn btn-success']);
                    ?>
                    <p class="reg show">
                        <span>Нет аккаунта?</span>
                        <a href="<?=\yii\helpers\Url::to(['user/registration'])?>" class="form-link-reg">Зарегистрироваться!</a>
                    </p>
                    <p class="reg show">
                        <span>Уже есть аккаунт?</span>
                        <a href="<?=\yii\helpers\Url::to(['user/login'])?>" class="form-link-reg">Войти!</a>
                    </p>
                    <?php
                else:
                    ?>
                    <p>Ссылка сброса пароля для пользователя <?=Html::encode($mail) ?></p>
                    <p>была выслана на вашу почту!</p>
                    <p>Пароль можно будет сбросить до <?=Html::encode($res_dt)?></p>
                    <?= Html::a('На главную!', ['user/login', ], ['class' => 'btn btn-success']) ?>
                    <?php
                endif;
                ActiveForm::end();
                ?>

            </div>
        </div>
    </div>
</section>
