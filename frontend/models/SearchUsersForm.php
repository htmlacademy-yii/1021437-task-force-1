<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use yii\db\Expression;

class SearchUsersForm extends ActiveRecord
{

    public $categories;
    public $searchByName;
    public $free;
    public $online;
    public $reviews;
    public $inFavorite;

    public $rating;
    public $countTasks;
    public $popularity;

    public function rules()
    {
        return [
            [['categories', 'searchByName', 'online', 'free', 'reviews', 'inFavorite'], 'safe'],
            [['searchByName'], 'string', 'max' => 255],
            [['online', 'free', 'reviews', 'inFavorite'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'searchByName' => 'Поиск по имени',
            'free' => 'Сейчас свободен',
            'online' => 'Сейчас онлайн',
            'reviews' => 'Есть отзывы',
            'inFavorite' => 'В избранном',
        ];
    }

    public function getUsers($sort)
    {
        $users = User::find()
            ->with('tasks0')
            ->with('feedbacks0')
            ->with('userCategories.categories')
            ->with('profile')
            ->where('exists(SELECT `id` FROM user_category WHERE user.id = user_id)')
            ->groupBy('user.id');

        if ($sort === 'popularity') {
            $users->joinWith('profile')->orderBy('profile.views_account DESC');
        } elseif ($sort === 'raiting') {
            $users->joinWith('profile')->orderBy('profile.rating DESC');
        } elseif ($sort === 'countTasks') {
            //сортировка по количеству заказов, где пользователь был назначен исполнителем
            $users->joinWith('tasks0')
                ->select('user.*, COUNT(task.executor_id) AS order_count')
                ->orderBy('order_count DESC');
        } else {
            $users->orderBy('registration_at DESC');
        }

        if ($this->categories) {
            $users->joinWith('userCategories')
                ->andWhere(['categories_id' => $this->categories]);
        }
        if ($this->free) {
            $users->joinWith('tasks0')
                ->andWhere(['IN', 'task.status', ['canceled', 'success', 'failed']]);
        }
        if ($this->online) {
            $lastVisit = date('Y-m-d H:i:s', strtotime('-30 mins'));
            $users->joinWith('profile')
                ->andWhere(['>=', 'last_visit', $lastVisit]);
        }
        if ($this->inFavorite) {
            $users->andWhere('exists ' .
                '(SELECT * FROM favorite WHERE
                favorite.user_id = 21
                AND favorite_id = user.id)'
            );
        }
        if ($this->reviews) {
            $users->leftJoin('feedback f', 'f.executor_id = user.id')
                ->addGroupBy('user.id');
        }
        if ($this->searchByName) {
            $users->andWhere(new Expression('user.name LIKE :userName', [':userName' => '%' . $this->searchByName . '%']));
        }

        return $users->limit(5)->all();
    }

}
