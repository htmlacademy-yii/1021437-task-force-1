<?php

use frontend\assets\MapAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use Task\classes\utils\ViewRatingStars;
use yii\widgets\ActiveForm;

$this->title = 'Task №' . $task->id . ' | Title: ' . $task->title;

if (!empty($task->address)) {
    MapAsset::register($this);
}
?>
<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= Html::encode($task->title); ?></h1>
                    <span>Размещено в категории
                        <a href="#" class="link-regular"><?= $task->category->name; ?></a>
                    <?= Yii::$app->formatter->asRelativeTime($task->created_at); ?></span>
                </div>
                <b class="new-task__price new-task__price--clean content-view-price"><?= Html::encode($task->budget); ?><b> ₽</b></b>
                <div class="new-task__icon new-task__icon--<?= $task->category->category_icon; ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p>
                    <?= Html::encode($task->description); ?>
                </p>
            </div>
            <?php if (count($task->taskAttachments) > 0):  ?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <?php foreach ($task->taskAttachments as $item): ?>
                        <a href="/<?= $item->file_link; ?>" download="/<?= $item->file_link; ?>"><?= Html::encode($item->file_name); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($task->address)): ?>
                <div class="content-view__location">
                    <h3 class="content-view__h3">Расположение</h3>
                    <div class="content-view__location-wrapper">
                        <div class="content-view__map">
                            <div id="map" style="width: 361px; height: 292px"></div>
                        </div>
                        <div class="content-view__address">
                            <span class="address__town">Москва</span><br>
                            <span><?= $task->address; ?></span>
                            <p>Вход под арку, код домофона 1122</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="content-view__action-buttons">
            <?php if (($action->getPrivateName() === $currentTask::ACTION_RESPOND) && empty($response)): ?>
                <button class=" button button__big-color response-button open-modal"
                        type="button" data-for="response-form">Откликнуться</button>
            <?php endif; ?>
            <?php if($action->getPrivateName() === $currentTask::ACTION_REFUSE ): ?>
            <button class="button button__big-color refusal-button open-modal"
                    type="button" data-for="refuse-form">Отказаться</button>
            <?php endif; ?>
            <?php if($action->getPrivateName() === $currentTask::ACTION_SUCCESS) : ?>
            <button class="button button__big-color request-button open-modal"
                    type="button" data-for="complete-form">Завершить</button>
            <?php endif; ?>
            <?php if ($action->getPrivateName() === $currentTask::ACTION_CANCEL): ?>
                <button class="button button__big-color request-button open-modal"
                        type="button" data-for="cancel-form">Отменить</button>
            <?php endif; ?>
        </div>
    </div>
    <?php if(Yii::$app->user->id === $task->author_id || !empty($response)): ?>
        <div class="content-view__feedback">
            <h2>Отклики <span>(<?= $task->getTotalResponses(); ?>)</span></h2>
            <div class="content-view__feedback-wrapper">
                <?php foreach($task->responses as $user): ?>
                    <?php if (Yii::$app->user->id === $user->executor->id || Yii::$app->user->id === $task->author_id ): ?>
                        <div class="content-view__feedback-card">
                            <div class="feedback-card__top">
                                <a href="<?= Url::to(['users/view', 'id' => $user->executor->id]); ?>">
                                    <img src="/img/man-glasses.jpg" width="55" height="55">
                                </a>
                                <div class="feedback-card__top--name">
                                    <p><a href="<?= Url::to(['users/view', 'id' => $user->executor->id]); ?>" class="link-regular"><?= Html::encode($user->executor->name); ?></a></p>
                                    <?= ViewRatingStars::getRating($user->executor->profile->rating); ?>
                                    <b><?= $user->executor->profile->rating; ?></b>
                                </div>
                                <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($user->created_at); ?></span>
                            </div>
                            <div class="feedback-card__content">
                                <p>
                                    <?= Html::encode($user->text_responses); ?>
                                </p>
                                <span><?= $user->budget; ?> ₽</span>
                            </div>
                            <?php if ($user->status_response === 'new' && $task->status === 'new' && Yii::$app->user->id === $task->author_id): ?>
                                <div class="feedback-card__actions">
                                    <a href="<?= Url::to(['tasks/accept-response', 'userId' => $user->executor->id, 'taskId' => $task->id]); ?>" class="button__small-color request-button button"
                                       type="button">Подтвердить</a>
                                    <a href="<?= Url::to(['tasks/reject-response', 'userId' => $user->executor->id, 'taskId' => $task->id]); ?>" class="button__small-color refusal-button button"
                                       type="button">Отказать</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="/img/man-brune.jpg" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?= Html::encode($task->author->name); ?></p>
                </div>
            </div>
            <p class="info-customer"><span>
                    <?= Yii::t(
                        'app',
                        '{n, plural, =0{заданий} =1{# задание} one{# задание} few{# задания} many{# заданий} other{# задания}}',
                        ['n' => $task->author->getTotalTasksForAuthor()]
                    ); ?>
                    </span>
                <span class="last-">
                    <?= Yii::$app->formatter->asRelativeTime($task->author->profile->last_visit); ?>
                </span>
            </p>
            <a href="<?= Url::to(['users/view', 'id' => $task->author->id]); ?>" class="link-regular">
                Смотреть профиль
            </a>
        </div>
    </div>
    <div id="chat-container">
        <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat"></chat>
    </div>
</section>
<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
    <?php $form = ActiveForm::begin([
        'method' => 'POST',
        'action' => ['tasks/respond'],
        'fieldConfig' => [
            'errorOptions' => ['style' => 'color: #FF116E', 'class' => 'help-block']
        ]
    ]); ?>
        <?= $form->field(
            $modelResponse,
            'budget',
            ['template' => '<p>{label}{input}{error}</p>', 'labelOptions' => ['class' => 'form-modal-description']]
        )->input('text', ['class' => 'response-form-payment input input-middle input-money']); ?>
        <?= $form->field(
            $modelResponse,
            'text',
            ['template' => '<p>{label}{input}{error}</p>', 'labelOptions' => ['class' => 'form-modal-description']]
        )->input('text', ['class' => 'input textarea']) ?>
    <?= $form->field($modelResponse, 'taskId', ['template' => '{input}{error}'])->hiddenInput(['value' => $task->id]); ?>
        <?= Html::submitButton('Отправить', ['class' => 'button modal-button']); ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php $form = ActiveForm::begin([
        'method' => 'POST',
        'action' => Url::toRoute(['tasks/complete', 'id' => $task->id]),
        'fieldConfig' => ['errorOptions' => ['style' => 'color: #FF116E', 'class' => 'help-block']]
    ]); ?>
        <?= $form->field(
            $modelComplete,
            'status',
            ['template' => "{input}{error}"]
        )->radioList(
            $modelComplete->statusValue,
            [
                'unselect' => null,
                'item' => function ($index, $label, $name, $checked, $value) {
                    return
                        '<input id="completion-radio--'.$value.'" type="radio" name="CompleteTaskForm[status]" value='.$value.' class="visually-hidden completion-input completion-input--'.$value.'"><label for="completion-radio--'.$value.'" class="completion-label completion-label--'.$value.'">' . $label . '</label>';
                },
            ]
            ); ?>
        <?= $form->field(
            $modelComplete,
            'text',
            ['template' => '<p>{label}{input}{error}</p>', 'labelOptions' => ['class' => 'form-modal-description']]
        )->textarea([
            'class' => 'input textarea',
            'rows' => 4,
            'placeholder' => 'Введите комментарий'
        ]); ?>
    <p class="form-modal-description">
        Оценка
        <div class="feedback-card__top--name completion-form-star">
            <span class="star-disabled"></span>
            <span class="star-disabled"></span>
            <span class="star-disabled"></span>
            <span class="star-disabled"></span>
            <span class="star-disabled"></span>
        </div>
    </p>
        <?= $form->field(
            $modelComplete,
            'rating',
            ['template' => "{input}{error}"]
        )->hiddenInput(['id' => 'rating']) ?>
        <?= Html::submitButton('Отправить', ['class' => 'button modal-button']); ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<?php if($action->getPrivateName() === $currentTask::ACTION_REFUSE ): ?>
    <section class="modal form-modal refusal-form" id="refuse-form">
        <h2>Отказ от задания</h2>
        <p>
            Вы собираетесь отказаться от выполнения задания.
            Это действие приведёт к снижению вашего рейтинга.
            Вы уверены?
        </p>
        <button class="button__form-modal button" id="close-modal" type="button">Отмена</button>
        <?php ActiveForm::begin([
            'method' => 'POST',
            'action' => Url::toRoute(['tasks/failed-task/', 'id' => $task->id])
        ]); ?>
        <?= Html::submitButton('Отказаться', ['class' => 'button__form-modal refusal-button button']) ?>
        <?php ActiveForm::end(); ?>
        <button class="form-modal-close" type="button">Закрыть</button>
    </section>
<?php endif; ?>
<section class="modal form-modal refusal-form" id="cancel-form">
    <h2>Отмена задания</h2>
    <p>
        Вы собираетесь отменить задание.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal" type="button">Отмена</button>
    <?php ActiveForm::begin([
        'method' => 'POST',
        'action' => Url::toRoute(['tasks/cancel/', 'id' => $task->id])
    ]); ?>
    <?= Html::submitButton('Отменить', ['class' => 'button__form-modal refusal-button button']) ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
