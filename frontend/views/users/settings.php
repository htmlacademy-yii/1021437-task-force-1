<?php

use frontend\models\Profile;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Настройки аккаунта';
?>
<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php $form = ActiveForm::begin([
        'method' => 'POST',
        'id' => 'account',
        'action' => ['users/settings/', 'id' => Yii::$app->request->get('id')],
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
        'fieldConfig' => [
            'options' => [
                'tag' => false,
            ],
            'errorOptions' => ['style' => 'color: #FF116E']
        ],
        'enableClientScript' => false,
    ]); ?>
        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <?= Html::img( '/' . $model->avatar, ['width' => '156', 'height' => '156']); ?>
                    <?= $form->field(
                        $model,
                        'avatar',
                        [
                            'template' => '{input}{label}{error}',
                            'labelOptions' => ['class' => 'link-regular']
                        ]
                    )->input(
                        'file', ['id' => 'upload-avatar']
                    ); ?>
                </div>
                <div class="account__redaction">
                    <div class="account__input account__input--name">
                        <?= $form->field(
                            $model,
                            'name',
                            ['options' => [
                                'tag' => false,
                            ],]
                        )->input(
                            'text', ['class' => 'input textarea', 'placeholder' => 'Укажите имя']
                        ); ?>
                    </div>
                    <div class="account__input account__input--email">
                        <?= $form->field(
                            $model,
                            'email',
                            ['options' => [
                                'tag' => false,
                            ],]
                        )->input(
                            'email', ['class' => 'input textarea', 'placeholder' => 'Укажите почту']
                        ); ?>
                    </div>
                    <div class="account__input account__input--name">
                        <?= $form->field(
                            $model,
                            'town'
                        )->dropDownList(
                            $cities,
                            [
                                'options' => [$model->town => ['selected' => true]],
                                'size' => 1,
                                'class' => 'multiple-select input multiple-select-big',
                                'unselect' => null
                            ]
                        ); ?>
                    </div>
                    <div class="account__input account__input--date">
                        <?= $form->field(
                            $model,
                            'dateBirthday',
                            ['options' => [
                                'tag' => false,
                                ],
                            ]
                        )->input(
                            'date', ['class' => 'input-middle input input-date', 'placeholder' => $model->dateBirthday]
                        ); ?>
                    </div>
                    <div class="account__input account__input--info">
                        <?= $form->field(
                            $model,
                            'info'
                        )->textarea(
                            ['class' => 'input textarea', 'rows' => '7', 'placeholder' => 'Ваш текст']
                        ) ?>
                    </div>
                </div>
            </div>
            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= $form->field(
                        $model,
                        'categories'
                    )->checkboxList(
                        $categories,
                        [
                            'unselect' => null,
                            'tag' => false,
                            'item' => function($index, $label, $name, $checked, $value) {
                                $checkedLabel = $checked ? 'checked' : '';
                                return "<input class ='visually-hidden checkbox__input' id=$index $checkedLabel type='checkbox'
                                        name=$name value=$value $checked><label for=$index>$label</label>";
                            },
                        ]
                    )->label(false); ?>
                </div>
            </div>
            <h3 class="div-line">Безопасность</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <?= $form->field(
                        $model,
                        'password'
                    )->input('password', ['class' => 'input textarea'])
                    ; ?>
                </div>
                <div class="account__input">
                    <?= $form->field(
                        $model,
                        'password_confirmation'
                    )->input('password', ['class' => 'input textarea'])
                    ; ?>
                </div>
            </div>

            <h3 class="div-line">Фото работ</h3>

            <div class="account__redaction-section-wrapper account__redaction">
                <span class="dropzone">Выбрать фотографии</span>
            </div>

            <h3 class="div-line">Контакты</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <?= $form->field(
                        $model,
                        'phone',
                        ['options' => [
                            'tag' => false,
                        ],]
                    )->input(
                        'tel', ['class' => 'input textarea', 'placeholder' => 'Укажите номер']
                    ); ?>
                </div>
                <div class="account__input">
                    <?= $form->field(
                        $model,
                        'skype',
                        ['options' => [
                            'tag' => false,
                        ],]
                    )->input(
                        'tel', ['class' => 'input textarea', 'placeholder' => 'Укажите логин пользователя']
                    ); ?>
                </div>
                <div class="account__input">
                    <?= $form->field(
                        $model,
                        'telegram',
                        ['options' => [
                            'tag' => false,
                        ],]
                    )->input(
                        'text', ['class' => 'input textarea', 'placeholder' => 'Укажите логин пользователя']
                    ); ?>
                </div>
            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= $form->field(
                        $model,
                        'notifications_new_message',
                        [
                            'options' => ['tag' => false],
                            'template' => '{input}{label}'
                        ]
                    )->checkbox(
                        [
                            'class' => 'visually-hidden checkbox__input',
                            'uncheck' => null
                        ], false); ?>
                    <?= $form->field(
                        $model,
                        'notifications_task_actions',
                        [
                            'options' => ['tag' => false],
                            'template' => '{input}{label}'
                        ]
                    )->checkbox(
                        [
                            'class' => 'visually-hidden checkbox__input',
                            'uncheck' => null
                        ], false); ?>
                    <?= $form->field(
                        $model,
                        'notifications_new_review',
                        [
                            'options' => ['tag' => false],
                            'template' => '{input}{label}'
                        ]
                    )->checkbox(
                        [
                            'class' => 'visually-hidden checkbox__input',
                            'uncheck' => null
                        ], false); ?>
                </div>
                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <?= $form->field(
                        $model,
                        'public_contacts',
                        [
                            'options' => ['tag' => false],
                            'template' => '{input}{label}'
                        ]
                    )->checkbox([
                        'class' => 'visually-hidden checkbox__input',
                        'uncheck' => null
                    ], false); ?>
                    <?= $form->field(
                        $model,
                        'hidden_profile',
                        [
                            'options' => ['tag' => false],
                            'template' => '{input}{label}'
                        ]
                    )->checkbox([
                        'class' => 'visually-hidden checkbox__input',
                        'uncheck' => null
                    ], false); ?>
                </div>
            </div>
        </div>
        <?= Html::button('Сохранить изменения', ['type' => 'submit', 'class' => 'button']); ?>
    <?php ActiveForm::end(); ?>
</section>
<script src="/js/dropzone.js"></script>
<script>
    Dropzone.autoDiscover = false;

    var dropzone = new Dropzone(".dropzone", {
        url: window.location.href,
        maxFiles: 6,
        headers: {
            'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
        },
        uploadMultiple: true,
        dictDefaultMessage: "",
        acceptedFiles: 'image/*',
        previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы"></a>'
    });

    var lightbulb = document.getElementsByClassName('header__lightbulb')[0];
    lightbulb.addEventListener('mouseover', function () {
        fetch('/events/index');
    });

</script>
