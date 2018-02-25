<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form app\models\AuthForm */
/* @var $model app\models\User */

$this->title = 'Events | Вход в систему';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page login'], 'keywords');
?>

<section class="main-auth">
    <div class="section-table">
        <div class="section-row">
            <div class="section-cell">
                <?php
                    $form = ActiveForm::begin(
                        [
                            'action' => ['site/login'],
                            'method' => 'post',
                            'options'  => [
                                'class' => 'form-auth',
                            ]
                        ]
                    );
                ?>
                    <h2><i class="fa fa-sun-o" aria-hidden="true"></i> Events</h2>
                    <hr class="hr-toh2">
                    <p class="capt show">Войдите для работы</p>
                    <?php if (isset($err1)): ?>
                        <p class="capt show" style="font-size: 12px; color: #DF4326;"><?=Html::encode($err1)?></p>
                    <?php endif; ?>
                    <?= $form->field($model, 'mail',
                                        ['inputOptions' => [
                                            'placeholder' => 'Введите email',
                                            'class' => 'form-control'
                                            ]
                                        ]
                                    )->textInput(['autofocus' => true])
                    ?>

                    <?= $form->field($model, 'upass',
                                        ['inputOptions' => [
                                            'placeholder' => 'Введите пароль',
                                            'class' => 'form-control'
                                            ]
                                        ]
                                    )->passwordInput()
                    ?>
                    <?php
                        echo $form->field($model, 'captcha')->widget(
                                Captcha::className(),
                                ['options' => [
                                    'placeholder' => 'Введите капчу',
                                    'class' => 'form-control'
                                ]]
                            )->label('Капча');

                    ?>
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-success']) ?>

                    <p class="reg show">
                        <span>Нет аккаунта?</span>
                        <a href="<?=\yii\helpers\Url::to(['site/registration'])?>" class="form-link-reg">Зарегистрироваться!</a>
                    </p>
                    <p class="reg show">
                        <span>Забыли пароль?</span>
                        <a href="<?=\yii\helpers\Url::to(['site/restore'])?>" class="form-link-reg">Восстановить!</a>
                    </p>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</section>