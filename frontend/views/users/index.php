<?php
/**
 * @var $users array
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Исполнители на Task-Force';
$typeSort = Yii::$app->request->get('sort');
?>
<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item <?php if($typeSort === 'raiting'): ?> user__search-item--current <?php endif; ?>">
                <?= Html::tag('a', 'Рейтингу', ['class' => 'link-regular', 'href' => '?sort=raiting']); ?>
            </li>
            <li class="user__search-item <?php if($typeSort === 'countTasks'): ?> user__search-item--current <?php endif; ?>">
                <?= Html::tag('a', 'Числу заказов', ['class' => 'link-regular', 'href' => '?sort=countTasks']); ?>
            </li>
            <li class="user__search-item <?php if($typeSort === 'popularity'): ?> user__search-item--current <?php endif; ?>">
                <?= Html::tag('a', 'Популярности', ['class' => 'link-regular', 'href' => '?sort=popularity']); ?>
            </li>
        </ul>
    </div>
    <?php foreach ($users as $user): ?>
        <div class="content-view__feedback-card user__search-wrapper">
            <div class="feedback-card__top">
                <div class="user__search-icon">
                    <a href="#"><img src="./img/man-glasses.jpg" width="65" height="65"></a>
                    <span><?= $user->getTotalTasks(); ?> заданий</span>
                    <span><?= $user->getTotalFeedbacks(); ?> отзывов</span>
                </div>
                <div class="feedback-card__top--name user__search-card">
                    <p class="link-name"><a href="#" class="link-regular"><?= Html::encode($user->name); ?></a></p>
                    <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                    <b><?= $user->profile->rating ?? 0; ?></b>
                    <p class="user__search-content">
                        <?= Html::encode($user->profile->user_info); ?>
                    </p>
                </div>
                <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($user->profile->last_visit); ?></span>
            </div>
            <div class="link-specialization user__search-link--bottom">
                <?php foreach ($user->userCategories as $category): ?>
                    <a href="#" class="link-regular"><?= $category->categories->name; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['class' => 'search-task__form', 'name' => 'users'],
            'action' => ['users/index'],
        ]); ?>
            <?= Html::beginTag('fieldset', ['class' => 'search-task__categories']); ?>
                <?= Html::tag('legend', 'Категории') ?>
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
                    ]
                ) ; ?>
            <?= Html::endTag('fieldset'); ?>
            <?= Html::beginTag('fieldset', ['class' => 'search-task__categories']); ?>
                <?= Html::tag('legend', 'Дополнительно') ?>
                <?= $form->field(
                        $model,
                        'free',
                        ['options' => ['tag' => false], 'template' => '{input}{label}'])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input',
                        'uncheck' => null],
                    false); ?>
                <?= $form->field(
                        $model,
                        'online',
                        ['options' => ['tag' => false], 'template' => '{input}{label}'])
                    ->checkbox([
                            'class' => 'visually-hidden checkbox__input',
                            'uncheck' => null],
                        false); ?>
                <?= $form->field(
                        $model,
                        'reviews',
                        ['options' => ['tag' => false], 'template' => '{input}{label}'])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input',
                        'uncheck' => null],
                    false); ?>
                <?= $form->field(
                        $model,
                        'inFavorite',
                        ['options' => ['tag' => false], 'template' => '{input}{label}'])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input',
                        'uncheck' => null],
                    false); ?>
            <?= Html::endTag('fieldset') ?>
            <?= $form->field(
                $model,
                'searchByName',
                ['options' => ['tag' => false], 'template' => '{label}{input}']
            )->textInput(['class' => 'input-middle input'])
            ->label('ПОИСК ПО ИМЕНИ', ['class' => 'search-task__name']); ?>
            <?= Html::submitButton('Искать', ['class' => 'button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</section>
