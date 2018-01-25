<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
$mv = $model;
//echo \app\components\Debug::d($mv,'model mv',1);
if (!empty($chich)){
    //echo $chich . "</br>";
    ?>
    <div class="container">

        <p>Вы ввели следующую информацию:</p>

        <ul class="container">
            <li><label>Name</label>:  <?= Html::encode($model->uname) ?></li>
            <li><label>Email</label>: <?= Html::encode($model->mail)  ?></li>
            <li><label>Pass</label>:  <?= Html::encode($model->upass) ?></li>
        </ul>

    </div>

    <?php
}
?>

<section class="main-auth">
    <div class="section-table">
        <div class="section-row">
            <div class="section-cell">
                <?php
                $form = ActiveForm::begin(
                    [
                        'action' => ['site/sign-up'],
                        'method' => 'post',
                        'options'  => [
                            'class' => 'form-auth',
                        ]
                    ]
                )
                ?>
                <h2><i class="fa fa-sun-o" aria-hidden="true"></i> Events</h2>
                <hr class="hr-toh2">
                <p class="capt">Регистрация для получения доступа</p>
                <?= $form->field($model, 'mail',
                    ['inputOptions' => [
                        'placeholder' => 'Введите ваш email',
                        'class' => 'form-control'
                    ]
                    ]
                )->textInput(['autofocus' => true])
                ?>

                <?= $form->field($model, 'uname',
                    ['inputOptions' => [
                        'placeholder' => 'Введите ваше имя',
                        'class' => 'form-control'
                    ]
                    ]
                )
                ?>

                <?= $form->field($model, 'upass',
                    ['inputOptions' => [
                        'placeholder' => 'Введите ваш пароль',
                        'class' => 'form-control'
                    ]
                    ]
                )->passwordInput()
                ?>

                <label class="btn pl0 sogl show">
                    <input type="checkbox" name='email2'  style="display: none;" required>
                    <i class="fa fa-square-o fa-2x"></i>
                    <i class="fa fa-check-square-o fa-2x"></i>
                    <span>Согласен с правилами</span>
                </label>

                <button type="submit" class="btn btn-success show">Зарегистрироваться</button>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</section>