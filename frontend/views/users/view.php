<?php
use yii\helpers\Html;
use yii\helpers\Url;
use Task\classes\utils\ViewRatingStars;

$this->title = 'Профиль - ' . $user->name;
?>
<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="/img/man-hat.png" width="120" height="120" alt="Аватар пользователя">
            <div class="content-view__headline">
                <h1><?= Html::encode($user->name); ?>  </h1>
                <p><?php echo $user->city->city . ', '; ?>
                    <?= Yii::t(
                        'app',
                        '{n, plural, =0{лет} =1{год} one{# год} few{# года} many{# года} other{# год}}',
                        ['n' => (new \DateTime())->diff(new \DateTime($user->profile->birthday_at))->y]
                    ); ?>
                </p>
                <div class="profile-mini__name five-stars__rate">
                    <?= ViewRatingStars::getRating($user->profile->rating);  ?>
                    <b><?= $user->profile->rating; ?></b>
                </div>
                <b class="done-task">Выполнил
                    <?= Yii::t(
                        'app',
                        '{n, plural, =0{нет заказов} =1{один заказ} one{# заказ} few{# заказа} many{# заказа} other{# заказа}}',
                        ['n' => $user->getTotalTasks()]
                    ); ?>
                </b>
                <b class="done-review">Получил
                    <?= Yii::t(
                        'app',
                        '{n, plural, =0{нет отзывов} =1{один отзыв} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзыва}}',
                        ['n' => $user->getTotalFeedback()]
                    ); ?>
                </b>
            </div>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <span>Был на сайте <?= Yii::$app->formatter->asRelativeTime($user->profile->last_visit); ?></span>
                <a href="#"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= Html::encode($user->profile->user_info); ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach ($user->userCategories as $category): ?>
                        <a href="#" class="link-regular"><?= $category->categories->name; ?></a>
                    <?php endforeach; ?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="#"><?= Html::encode($user->profile->phone); ?></a>
                    <a class="user__card-link--email link-regular" href="#"><?= Html::encode($user->email); ?></a>
                    <a class="user__card-link--skype link-regular" href="#"><?= Html::encode($user->profile->skype); ?></a>
                </div>
            </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <a href="#"><img src="/img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="/img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="/img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>
            </div>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отзывы<span>(<?= $user->getTotalTasks(); ?>)</span></h2>
        <div class="content-view__feedback-wrapper reviews-wrapper">
            <?php foreach ($user->tasks0 as $item): ?>
                <div class="feedback-card__reviews">
                    <p class="link-task link">Задание
                        <a href="<?= Url::to(['tasks/view', 'id' => $item->id]); ?>"
                           class="link-regular">«<?= $item->title; ?>»</a>
                    </p>
                    <div class="card__review">
                        <a href="#"><img src="/img/man-glasses.jpg" width="55" height="54"></a>
                        <div class="feedback-card__reviews-content">
                            <p class="link-name link">
                                <a href="<?= Url::to(['users/view', 'id' => $item->author_id]); ?>" class="link-regular">
                                    <?= Html::encode($item->author->name); ?>
                                </a>
                            </p>
                            <p class="review-text">
                                <?= Html::encode($item->feedback->comment); ?>
                            </p>
                        </div>
                        <div class="card__review-rate">
                            <?php $color = ($item->feedback->rating >= 4 ) ? 'five-rate' : 'three-rate'; ?>
                            <p class="<?= $color; ?> big-rate"><?= $item->feedback->rating; ?><span></span></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">

    </div>
</section>
