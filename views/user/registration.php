<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form app\models\AuthForm */
/* @var $model app\models\User */

$this->title = 'Events | Регистрация';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page registratioin'], 'keywords');
?>

<section class="main-auth">
    <div class="section-table">
        <div class="section-row">
            <div class="section-cell">
                <?php
                $form = ActiveForm::begin(
                    [
                        'action' => ['user/registration'],
                        'method' => 'post',
                        'options'  => [
                            'class' => 'form-auth',
                        ]
                    ]
                );
                ?>
                <h2><i class="fa fa-sun-o" aria-hidden="true"></i> Events</h2>
                <hr class="hr-toh2">
                <p class="capt show">Регистрация пользователя</p>

                <?php if (Yii::$app->session->hasFlash('registrated')): ?>
                    <p class="alert-success p5">
                        <?php echo Html::encode(Yii::$app->session->getFlash('registrated')); ?>
                    </p>
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
                ])->passwordInput()
                ?>
                <?= $form->field($model, 'upass_repeat',
                    ['inputOptions' => [
                        'placeholder' => 'Повторите пароль',
                        'class' => 'form-control'
                    ]
                ])->passwordInput();
                ?>
                <?= $form->field($model, 'uname',
                    ['inputOptions' => [
                        'placeholder' => 'Введите имя',
                        'class' => 'form-control'
                    ]
                    ]
                )->textInput(['autofocus' => true])
                ?>
                <?php
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

                ?>
                <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']) ?>

                <p class="reg show">
                    <span>Уже есть аккаунт?</span>
                    <a href="<?=\yii\helpers\Url::to(['user/login'])?>" class="form-link-reg">Войти!</a>
                </p>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</section>