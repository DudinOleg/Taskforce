<?php

use app\helpers\UIHelper;
use app\models\Task;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

$this->title = 'Мои задания';
/* @var $this \yii\web\View */
/* @var $menuItems array */
/* @var $tasks Task[] */

?>

<div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <?=Menu::widget([
        'options' => ['class' => 'side-menu-list'], 'activeCssClass' => 'side-menu-item--active',
        'itemOptions' => ['class' => 'side-menu-item'],
        'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
        'items' => $menuItems
    ]); ?>
</div>
<div class="left-column left-column--task">
    <h3 class="head-main head-regular">Новые задания</h3>
    <?php foreach ($tasks as $model): ?>
        <?=$this->render('//partials/_task', ['model' => $model]); ?>
    <?php endforeach; ?>
</div>