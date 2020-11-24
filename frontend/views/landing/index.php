<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<h2>Вход на сайт</h2>
<?php Pjax::begin(); ?>
    <?php if ($errors) : ?>
        <p class="has-error text-danger">Вы ввели неверный email/пароль!</p>
        <br>
    <?php endif; ?>
    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => '/',
        'enableAjaxValidation' => true,
        'validateOnSubmit' => false,
        'enableClientValidation'=> false
    ]); ?>
        <?= $form->field(
            $model,
            'email',
            [
                'labelOptions' => ['class' => 'form-modal-description'],
                'template' => "<p>{label}{input}{error}<p>"
            ]
        )->input('email', [
            'class' => 'enter-form-email input input-middle',
            'style' => 'margin-bottom:3px'
        ])->error(['style' => 'color: #FF116E;margin-bottom:30px;']); ?>
        <?= $form->field(
            $model,
            'password',
            [
                'labelOptions' => ['class' => 'form-modal-description'],
                'template' => "<p>{label}{input}{error}<p>"
            ]
        )->input('password', [
            'class' => 'enter-form-email input input-middle',
            'style' => 'margin-bottom:3px'
        ])->error(['style' => 'color: #FF116E;margin-bottom:30px;']); ?>
        <?= Html::submitButton('Войти', ['class' => 'button']); ?>
    <?php ActiveForm::end();  ?>
<?php Pjax::end(); ?>
<button class="form-modal-close" type="button">Закрыть</button>
