<?php
/**
 * @var Task $model
 */

use app\models\Category;
use app\models\Task;
use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View; ?>

<div class="task-card">
    <div class="header-task">
        <a href="<?=Url::toRoute(['tasks/view', 'id' => $model->id]); ?>" class="link link--block link--big">
            <?=Html::encode($model->name); ?>
        </a>
        <p class="price price--task"><?=$model->budget;?> ₽</p>
    </div>
    <p class="info-text"><?=Yii::$app->formatter->asRelativeTime($model->dt_add); ?></p>
    <p class="task-text"><?=Html::encode(BaseStringHelper::truncate($model->description, 200)); ?>
    </p>
    <div class="footer-task">
        <?php if ($model->location): ?>
            <p class="info-text town-text"><?= $model->location; ?></p>
        <?php endif ?>
        <p class="info-text category-text"><?=$model->category->name; ?></p>
        <a href="<?=Url::toRoute(['tasks/view', 'id' => $model->id]); ?>" class="button button--black">Смотреть Задание</a>
    </div>
</div>