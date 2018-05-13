<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 24.03.2018
 * Time: 18:44
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Events | Документы';

$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Приложение позволяет сохранять события и производить поиск по ним.'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page documents'], 'keywords');

?>


<div class="bill-inset">
    <div class="page-caption clearfix">
        <h2 class="pull-left" >Страница документов</h2>
    </div>

    <div class="page-hr">
        <hr>
    </div>
    <div class="page-content">

        <div class="load_user_documents">

            <div class="row">

                <div class="col-md-6">
                    <?php $userFilesInfo = app\models\File::getUserFilesInfo(); ?>
                    <h3>Файловый баланс</h3>
                    <p><strong>Загружено: </strong><?=$userFilesInfo['count']?></p>
                    <p><strong>Лимит: </strong><?=Yii::$app->params['fileMaxAmount']?></p>
                    <p><strong>Использовано трафика: </strong>
                        <?php echo Yii::$app->formatter->asShortSize($userFilesInfo['filesize'])?>
                    </p>
                    <p><strong>Лимит трафика: </strong>
                        <?php echo Yii::$app->formatter->asShortSize(Yii::$app->params['fileMaxSize'])?>
                    </p>
                </div>

                <div class="col-md-6">
                    <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data']
                    ]);
                    ?>

                    <?php if (\Yii::$app->session->hasFlash('loadFile')): ?>
                        <p class="alert-success p10 fz16">
                            <?=\Yii::$app->session->getFlash('loadFile')?>
                        </p>
                    <?php endif; ?>

                    <?=$form->field($model,'file[]')
                        ->fileInput(['multiple' => true ]) //, 'accept' => 'image/*'])
                        ->label('') ?>

                    <button>Отправить</button>

                    <?php ActiveForm::end();  ?>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if ($userFiles): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Имя</th>
                                    <th>Объем</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($userFiles as $k => $v): ?>
                                    <tr>
                                        <td><?=$v->id?></td>
                                        <td>
                                            <?=Html::a($v->name,['doc/show','id' => $v->id],['title' => 'Просмотр'])?>
                                        </td>
                                        <td><?=Yii::$app->formatter->asShortSize($v->filesize); ?></td>
                                        <td>
                                            <a class="" href="/doc/show?id=<?=$v->id?>" title="Просмотр">
                                                <span class="glyphicon glyphicon-eye-open"></span>
                                            </a>
                                            <a class="" href="/doc/upd?id=<?=$v->id?>" title="Обновление">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </a>
                                            <a class="" href="/doc/del?id=<?=$v->id?>" title="Удаление">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </a>
                                            <a class="" href="/doc/download?id=<?=$v->id?>" title="Скачать">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</div>

