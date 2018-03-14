<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 14.03.2018
 * Time: 16:50
 */

use yii\helpers\Url;

$this->title = 'Events | Аккаунт пользователя';
$this->registerMetaTag(['name' => 'description', 'content' => 'Приложение Events. Аккаунт пользователя'], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Events,App Events,Application Events, Page account'], 'keywords');

?>

<div class="account">
    <h3>Учетная запись пользователя</h3>
    <ul class="list-group">
        <li class="list-group-item">
            <i class="fa fa-gift" aria-hidden="true"></i>
            <a href="#">Мои заказы</a>
        </li>
        <li class="list-group-item">
            <i class="fa fa-star" aria-hidden="true"></i>
            <a href="#">Избранное</a>
        </li>
        <li class="list-group-item">
            <i class="fa fa-cogs" aria-hidden="true"></i>
            <a href="<?=Url::to(['user/change-user-info'],true);?>">Изменение личных данных</a>
        </li>
        <li class="list-group-item">
            <i class="fa fa-space-shuttle" aria-hidden="true"></i>
            <a href="<?=Url::to(['user/logout'],true);?>">Выход</a>
        </li>
    </ul>
</div>