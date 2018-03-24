<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use app\components\AuthLib;

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
                        'action' => [AuthLib::NOT_AUTHED_PATH],
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

                <?php if (Yii::$app->session->hasFlash('logined')): ?>
                    <h4 class="alert-danger p10 fz14" >
                        <?=Html::encode(Yii::$app->session->getFlash('logined'))?>
                    </h4>
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
                )->passwordInput();

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

                echo Html::submitButton('Войти', ['class' => 'btn btn-success']);
                ?>

                <p class="reg show">
                    <span>Нет аккаунта?</span>
                    <a href="<?=\yii\helpers\Url::to(['user/registration'])?>" class="form-link-reg">Зарегистрироваться!</a>
                </p>
                <p class="reg show">
                    <span>Забыли пароль?</span>
                    <a href="<?=\yii\helpers\Url::to(['user/restore'])?>" class="form-link-reg">Восстановить!</a>
                </p>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</section>