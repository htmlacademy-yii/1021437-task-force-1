<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация аккаунта';
?>
<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'options' => [
                'class' => 'registration__user-form form-create'
            ],
            'action' => ['registration/index'],
            'enableClientScript' => false,
            'fieldConfig' => [
                'options' => [
                    'tag' => false,
                ],
                'errorOptions' => ['style' => 'color: #FF116E']
            ]
        ]) ?>
            <?= $form->field(
                $model,
                'email',
                [
                    'labelOptions' => ['class' => isset($errors['password']) ? 'input-danger' : ''],
                    'errorOptions' => ['tag' => 'span']
                ]
            )->input('text',
                [
                    'autofocus' => true,
                    'class' => 'input textarea',
                    'placeholder' => 'kumarm@mail.ru'
                ]
            ); ?>
            <?= $form->field(
                $model,
                'name',
                [
                    'labelOptions' => ['class' => isset($errors['password']) ? 'input-danger' : ''],
                    'errorOptions' => ['tag' => 'span']
                ]
            )->input('text',
                [
                    'class' => 'input textarea',
                    'placeholder' => 'Мамедов Кумар'
                ]
            ); ?>
            <?= $form->field(
                $model,
                'city',
                ['errorOptions' => ['tag' => 'span']]
            )->dropDownList($cities, [
                'options' => [
                    $model->city => ['selected' => true],
                ],
                'size' => 1,
                'class' => 'multiple-select input town-select registration-town']); ?>
            <?= $form->field(
                $model,
                'password',
                [
                    'labelOptions' => ['class' => isset($errors['password']) ? 'input-danger' : ''],
                    'errorOptions' => ['tag' => 'span']
                ]
            )->passwordInput(['class' => 'input textarea']); ?>
            <?= Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</section>
