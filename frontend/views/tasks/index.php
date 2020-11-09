<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = 'Новые задачи';
?>
<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php /** @var array $tasks массив с задачами */
        foreach ($tasks as $task): ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="<?= Url::to(['tasks/view', 'id' => $task->id]); ?>" class="link-regular">
                        <h2><?= Html::encode($task->title); ?></h2>
                    </a>
                    <a class="new-task__type link-regular" href="#"><p><?= $task->category->name; ?></p></a>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task->category->category_icon; ?>"></div>
                <p class="new-task_description">
                    <?= Html::encode($task->description); ?>
                </p>
                <b class="new-task__price new-task__price--<?= $task->category->category_icon; ?>"><?= Html::encode($task->budget); ?><b> ₽</b></b>
                <p class="new-task__place"><?= Html::encode($task->address); ?></p>
                <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($task->created_at); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="new-task__pagination">
        <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current">
                <a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
        </ul>
    </div>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['class' => 'search-task__form', 'name' => 'test'],
            'action' => ['/tasks/index'],
        ]); ?>
            <?= Html::beginTag('fieldset', ['class' => 'search-task__categories']) ?>
                <?= Html::tag('legend', 'Категории'); ?>
                <?= Html::activeCheckboxList(
                    $model,
                    'categories',
                    $categories,
                    [
                        'unselect' => null,
                        'tag' => false,
                        'item' => function($index, $label, $name, $checked, $value) {
                            $checkedLabel = $checked ? 'checked' : '';
                            return "<input class ='visually-hidden checkbox__input' id=$index $checkedLabel type='checkbox' 
                                            name=$name value=$value $checked><label for=$index>$label</label>";
                        },
                    ]); ?>
            <?= Html::endTag('fieldset'); ?>
            <?= Html::beginTag('fieldset', ['class' => 'search-task__categories']) ?>
                <?= Html::tag('legend', 'Дополнительно'); ?>
                <?= $form
                    ->field(
                        $model,
                        'noFeedback',
                        ['options' => ['tag' => false], 'template' => '{input}{label}'])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input',
                        'uncheck' => null],
                        false); ?>
                <?= $form->field(
                    $model,
                    'remoteWork',
                    ['options' => ['tag' => false], 'template' => '{input}{label}'])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input',
                        'uncheck' => null],
                    false); ?>
            <?= Html::endTag('fieldset'); ?>
            <?= $form
                ->field(
                    $model,
                    'defaultPeriod',
                    ['options' => ['tag' => false], 'template' => '{label}{input}', 'labelOptions' => ['class' => 'search-task__name']]
                )
                ->dropDownList($model->periods, [
                    'options' => [
                        $model->defaultPeriod => ['Selected' => true],
                    ],
                    'class'=>'multiple-select input']); ?>
            <?= $form
                ->field(
                    $model,
                    'searchByName',
                    ['options' => ['tag' => false], 'template' => '{label}{input}', 'labelOptions' => ['class' => 'search-task__name']]
                )->textInput(
                    ['class' => 'input-middle input']); ?>
            <?= Html::submitButton('Искать', ['class' => 'button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</section>
