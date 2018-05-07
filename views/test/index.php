<?php
/* @var $this yii\web\View */
use app\components\Debug;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1>test/index</h1>

<?php
    //echo Debug::d($_SESSION);
?>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        echo Debug::d($_REQUEST,'request');
    ?>

    <form action="">
        <div class="form-group">
            <label for="id_publishedBefore">publishedBefore</label>
            <?php
            echo \kartik\date\DatePicker::widget([
                'language' => 'ru',
                'name' => 'publishedBefore',
                "value" =>  $publishedBefore,
                'options' => ['placeholder' => 'выберите дату', 'id' => 'id_publishedBefore'],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy',
                ]
            ]);
            ?>
        </div>
        <button type="submit">submit!</button>
    </form>

    <?php
        echo $publishedBefore;
    ?>

</div>
