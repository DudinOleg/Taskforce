<?php

/* @var $this yii\web\View */
/* @var $model LoginForm */

use app\models\LoginForm;
use yii\authclient\widgets\AuthChoice;
use yii\widgets\ActiveForm;
?>
<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'action' => ['auth/login']]); ?>
    <?= $form->field($model, 'email', ['labelOptions' => ['class' => 'form-modal-description'],
        'inputOptions' => ['class' => 'enter-form-email input input-middle']]); ?>
    <?= $form->field($model, 'password', [ 'labelOptions' => ['class' => 'form-modal-description'],
        'inputOptions' => ['class' => 'enter-form-email input input-middle']])->passwordInput(); ?>
        <button class="button" type="submit">Войти</button>
    <?php ActiveForm::end(); ?>
    <?= AuthChoice::widget([
        'baseAuthUrl' => ['auth/vk'],
        'popupMode' => false,
    ]); ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>