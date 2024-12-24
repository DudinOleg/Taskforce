<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\widgets\Menu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="page-header">
    <nav class="main-nav">
        <a href='/' class="header-logo">
            <img class="logo-image" src="/img/logotype.png" width=227 height=60 alt="taskforce">
        </a>
        <?php if (Yii::$app->controller->id !== 'auth'): ?>
        <div class="nav-wrapper">
            <?=Menu::widget([
                'options' => ['class' => 'nav-list'], 'activeCssClass' => 'list-item--active',
                'itemOptions' => ['class' => 'list-item'],
                'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
                'items' => [
                    ['label' => 'Все задания', 'url' => ['tasks/index']],
                    ['label' => 'Мои задания', 'url' => ['tasks/my']],
                    ['label' => 'Создать задание', 'url' => ['tasks/create']],
                    ['label' => 'Настройки', 'url' => ['user/settings']]
                ]
            ]); ?>
            <?php endif; ?>
        </div>
    </nav>
<?php if (Yii::$app->controller->id !== 'auth'): ?>
<?php $user = Yii::$app->user->identity; ?>
    <div class="user-block">
        <?php if ($user->avatar): ?>
            <a href="#">
                <img class="user-photo" src="<?= $user->avatar; ?>" width="55" height="55" alt="Аватар">
            </a>
        <?php endif ?>
        <div class="user-menu">
            <p class="user-name"><?=$user->name;?></p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <a href="<?=Url::toRoute(['user/settings']); ?>" class="link">Настройки</a>
                    </li>
                    <?php if ($user->is_contractor): ?>
                        <li class="menu-item">
                            <a href="<?= Url::toRoute(['user/view', 'id' => $user->getId()]); ?>" class="link">Мой
                                профиль</a>
                        </li>
                    <?php endif ?>
                    <li class="menu-item">
                        <a href="<?=Url::toRoute(['auth/logout']); ?>" class="link">Выход из системы</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>
</header>
<main class="main-content container <?=$this->params['main_class'] ?? ''; ?>">
    <?=$content; ?>
</main>
<div class="overlay"></div>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>