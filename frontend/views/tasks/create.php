<?php

use frontend\assets\AutoCompleteAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Создайте новую задачу';
AutoCompleteAsset::register($this);
?>

<section class="create__task">
    <h1>Публикация нового задания</h1>
    <div class="create__task-main">
        <?php $form = ActiveForm::begin([
            'id' => 'task-form',
            'action' => ['tasks/create'],
            'options' => [
                'enctype' => 'multipart/form-data',
                'class' => 'create__task-form form-create',
            ],
            'fieldConfig' => [
                'options' => [
                    'tag' => false,
                ],
                'errorOptions' => ['style' => 'color: #FF116E']
            ],
            'enableClientScript' => false,
        ]); ?>
        <?= $form->field(
            $model,
            'title')
            ->textInput([
                'placeholder' => 'Повесить полку',
                'class' => 'input textarea'
            ]); ?>
        <?= Html::tag('span', 'Кратко опишите суть работы'); ?>
        <?= $form->field(
            $model,
            'description')
            ->textarea([
                'rows' => '7',
                'placeholder' => 'Вставьте свой текст',
                'class' => 'input textarea'
            ]); ?>
        <?= Html::tag('span', 'Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться'); ?>
        <?= $form->field(
            $model,
            'category'
        )->dropDownList(
            $categories,
            [
                'options' => [$model->category => ['selected' => true]],
                'size' => 1,
                'class' => 'multiple-select input multiple-select-big',
                'unselect' => null
            ]
        ); ?>
        <?= Html::tag('span', 'Выберите категорию'); ?>
        <label>Файлы</label>
        <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
        <div class="create__file">
            <span>Добавить новый файл</span>
            <?php echo Yii::$app->session->getFlash('errorUploadFile'); ?>
        </div>
        <div class="create__file_error" style="color: #FF116E;display:none;">Прозошла ошибка при загрузке изображения</div>
        <?= $form->field(
            $model,
            'location'
        )->input(
            'search',
            [
                'id' => 'autoComplete',
                'autocomplete' => 'off',
                'class' => 'input-navigation input-middle input',
            ]
        ); ?>
        <?= Html::tag('span', 'Укажите адрес исполнения, если задание требует присутствия'); ?>
        <?= $form->field(
            $model,
            'latitude'
        )->hiddenInput(['id' => 'latitude'])->label(false); ?>
        <?= $form->field(
            $model,
            'longitude'
        )->hiddenInput(['id' => 'longitude'])->label(false); ?>
        <?= $form->field(
            $model,
            'cityId'
        )->hiddenInput(['id' => 'cityId'])->label(false); ?>
        <div class="create__price-time">
            <div class="create__price-time--wrapper">
                <?= $form->field(
                    $model,
                    'budget'
                )->textInput([
                    'class' => 'input textarea input-money',
                    'placeholder' => '1000'
                ]); ?>
                <?= Html::tag('span', 'Не заполняйте для оценки исполнителем'); ?>
            </div>
            <div class="create__price-time--wrapper">
                <?= $form->field(
                    $model,
                    'ends_at'
                )->input('date', [
                    'class' => 'input-middle input input-date',
                    'placeholder' => '10.11, 15:00'
                ]); ?>
                <?= Html::tag('span', 'Укажите крайний срок исполнения'); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>
            <?php if (!empty($errors)) : ?>
                <?php $labels = $model->attributeLabels() ?>
                <div class="warning-item warning-item--error">
                    <h2>Ошибки заполнения формы</h2>
                    <?php foreach ($errors as $title => $error) : ?>
                        <h3><?= $labels[$title]; ?></h3>
                        <p><?= $error[0]; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?= Html::submitButton('Опубликовать', ['class' => 'button', 'form' => 'task-form']); ?>
</section>
<script src="../js/dropzone.js"></script>
<script>
    var dropzone = new Dropzone("div.create__file", {
        url: '/tasks/create',
        paramName: "Attach",
        headers: {
            'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
        },
        init: function() {
            this.on("error", function(file, response) {
                document.querySelector('.create__file_error').style.display = 'block';
                document.querySelector('.dz-preview.dz-processing.dz-image-preview.dz-error .dz-details .dz-filename span').style.color = 'red';
                document.querySelector('.dz-error-message').style.display = 'none';
            });
            this.on("success", function(file, response) {
                document.querySelector('.create__file_error').style.display = 'none';
            });
        }
    });
</script>
