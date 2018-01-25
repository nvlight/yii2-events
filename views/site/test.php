<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 14.01.2018
 * Time: 19:21
 */

use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\HelloWidget;
use app\components\Debug;

?>


<div class="container">
    <h3>test</h3>
    <div class="row">
        <div class="col-md-7">
            <h5>starting</h5>
            <?php
                echo Debug::d($_SERVER);
                $real_link = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SERVER_PORT'].'/web/site';
                $restore_hash = "a33f9ebb21932b71fb26614313e96b3fd22d0807";
                $p[4] = Html::a('Восстановить доступ!', ['site/do-restore?hash='.$restore_hash ], ['class' => 'btn btn-success']);
                $p[4] = Html::a('Восстановить доступ!', [$real_link.'/web/site/do-restore?hash='.$restore_hash ], ['class' => 'btn btn-success']);
                echo $p[4];
            ?>
        </div>
    </div>
</div>
