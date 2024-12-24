<?php
/**
 * @var Task[] $models
 * @var Task $task
 * @var View $this
 * @var Category[] $categories
 * @var Pagination $pages
 */

use app\models\Category;
use app\models\Task;
use yii\data\Pagination;
use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Просмотр новых заданий';
?>

<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>
    <?php foreach ($models as $model): ?>
        <?=$this->render('//partials/_task', ['model' => $model]); ?>
    <?php endforeach; ?>
    <div class="pagination-wrapper">
        <?=LinkPager::widget([
            'pagination' => $pages,
            'prevPageCssClass' => 'pagination-item mark',
            'nextPageCssClass' => 'pagination-item mark',
            'pageCssClass' => 'pagination-item',
            'activePageCssClass' => 'pagination-item--active',
            'linkOptions' => ['class' => 'link link--page'],
            'nextPageLabel' => '',
            'prevPageLabel' => '',
            'maxButtonCount' => 5
        ]); ?>
    </div>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php $form = ActiveForm::begin(); ?>
                <h4 class="head-card">Категории</h4>
                <div class="checkbox-wrapper">
                    <?=Html::activeCheckboxList($task, 'category_id', array_column($categories, 'name', 'id'),
                        ['tag' => null, 'itemOptions' => ['labelOptions' => ['class' => 'control-label']]]); ?>
                </div>
                <h4 class="head-card">Дополнительно</h4>
            <div class="checkbox-wrapper">
                    <?=$form->field($task, 'noResponses')->checkbox(['labelOptions' => ['class' => 'control-label']]); ?>
            </div>
            <div class="checkbox-wrapper">
                <?=$form->field($task, 'noLocation')->checkbox(['labelOptions' => ['class' => 'control-label']]); ?></div>
                <h4 class="head-card">Период</h4>
                    <?=$form->field($task, 'filterPeriod', ['template' => '{input}'])->dropDownList([
                        '3600' => 'За последний час', '86400' => 'За сутки', '604800' => 'За неделю'
                    ], ['prompt' => 'Выбрать']); ?>
                <input type="submit" class="button button--blue" value="Искать">
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>