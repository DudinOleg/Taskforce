<?php

/**
 * @var Task $model
 * @var Category $categories
 * @var View $this
 */

use app\assets\DropzoneAsset;
use app\models\Category;
use app\models\Task;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Создание задания';
$this->params['main_class'] = 'main-content--center';

DropzoneAsset::register($this);
?>
<div class="add-task-form regular-form">
    <?php $form = ActiveForm::begin(); ?>
        <h3 class="head-main head-main">Публикация нового задания</h3>
    <?= $form->field($model, 'name'); ?>
    <?= $form->field($model, 'description')->textarea(); ?>
    <?= $form->field($model, 'category_id')->dropDownList(array_column($categories, 'name', 'id'),
    ['prompt' => 'Выбрать категорию']); ?>
    <?= $form->field($model, 'location'); ?>
    <div class="half-wrapper">
        <?= $form->field($model, 'budget')->input('text', ['class' => 'budget-icon']); ?>
        <?= $form->field($model, 'expire_dt')->input('date'); ?>
    </div>
    <p class="form-label">Файлы</p>
    <div class="new-file">
        <p class="add-file dz-clickable">Добавить новый файл</p>
    </div>
    <div class="files-previews">

    </div>

    <input type="submit" class="button button--blue" value="Опубликовать">
    <?php ActiveForm::end(); ?>
</div>
<?php
$uploadUrl = Url::toRoute(['tasks/upload']);
$this->registerJs(<<<JS
var myDropzone = new Dropzone(".new-file", {
    maxFiles: 4, url: "$uploadUrl", previewsContainer: ".files-previews",
    sending: function (none, xhr, formData) {
        formData.append('_csrf', $('input[name=_csrf]').val());
    }
    });
JS, View::POS_READY);
?>