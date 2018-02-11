<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */

$this->title = 'Events | Страница редактирования персональных данных';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page edit user data'], 'keywords');

?>

<div class="page-caption clearfix">
    <h2 class="pull-left" >Страница редактирования персональных данных</h2>
</div>
<div class="page-hr">
    <hr>
</div>

<?php if (Yii::$app->session->hasFlash('saved')): ?>
        <h3 class="alert-success p10" >
            <?=Html::encode(Yii::$app->session->getFlash('saved'))?>
        </h3>
<?php endif; ?>

<div class="site-changeuserinfo">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'uname') ?>
        <?= $form->field($model, 'upass')->passwordInput() ?>
        <?= $form->field($model, 'newpass1')->passwordInput() ?>
        <?= $form->field($model, 'newpass2')->passwordInput() ?>
        <?= $form->field($model, 'remains') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Изменить', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-changeuserinfo -->
