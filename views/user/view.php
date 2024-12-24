<?php

use app\helpers\UIHelper;use app\models\Opinion;
use app\models\Reply;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;use yii\web\View;

$this->title = 'Профиль пользователя';
/**
 * @var User $user
 * @var View $this
 */
?>

<div class="left-column">
    <h3 class="head-main"><?=Html::encode($user->name); ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <img class="card-photo" src="<?=$user->avatar; ?>" width="191"  alt="Фото пользователя">
            <div class="card-rate">
                <?=UIHelper::showStarRating($user->getRating(), 'big'); ?>
                <span class="current-rate"><?=$user->getRating(); ?></span>
            </div>
        </div>
        <p class="user-description">
            <?=Html::encode($user->description); ?>
        </p>
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">
                <?php foreach ($user->categories as $category): ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular"><?=$category->name; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info"><span class="country-info">Россия</span>,
                <span class="town-info"><?=$user->city->name;?></span><?php if ($user->bd_date): ?>, <span class="age-info"><?=$user->getAge(); ?></span> лет
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php if ($user->opinions): ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>

        <?php foreach ($user->opinions as $opinion): ?>
            <div class="response-card">
                <img class="customer-photo" src="<?=$opinion->owner->avatar; ?>" width="120" height="127" alt="Аватар заказчика">
                <div class="feedback-wrapper">
                    <p class="feedback">«<?=Html::encode($opinion->description); ?>»</p>
                    <p class="task">Задание «<a href="<?=Url::to(['tasks/view', 'id' => $opinion->task_id]); ?>"
                                                class="link link--small"><?=Html::encode($opinion->task->name); ?></a>» выполнено</p>
                </div>
                <div class="feedback-wrapper">
                    <?=UIHelper::showStarRating($opinion->rate); ?>
                    <p class="info-text"><span class="current-time"><?=Yii::$app->formatter->asRelativeTime($opinion->dt_add); ?></span></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd><?=$user->getAssignedTasks()->count(); ?> выполнено, <?=$user->fail_count;?> провалено</dd>
            <?php if ($position = $user->getRatingPosition()): ?>
                <dt>Место в рейтинге</dt>
                <dd><?=$position; ?> место</dd>
            <?php endif ?>
            <dt>Дата регистрации</dt>
            <dd><?=Yii::$app->formatter->asDate($user->dt_add); ?></dd>
            <dt>Статус</dt>
            <?php if (!$user->isBusy()): ?>
                <dd>Открыт для новых заказов</dd>
            <?php else: ?>
                <dd>Занят</dd>
            <?php endif ?>
        </dl>
    </div>
    <?php if ($user->isContactsAllowed(Yii::$app->user->getIdentity())): ?>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <?php if ($user->phone): ?>
                    <li class="enumeration-item">
                        <a href="tel:<?= $user->phone; ?>" class="link link--block link--phone"><?= $user->phone; ?></a>
                    </li>
                <?php endif ?>
                <li class="enumeration-item">
                    <a href="mailto:<?= $user->email; ?>" class="link link--block link--email"><?= $user->email; ?></a>
                </li>
                <?php if ($user->tg): ?>
                    <li class="enumeration-item">
                        <a href="https://t.me/<?= $user->tg; ?>"
                           class="link link--block link--tg">@<?= $user->tg; ?></a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    <?php endif ?>
</div>