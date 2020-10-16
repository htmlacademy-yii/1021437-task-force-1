<?php

namespace frontend\controllers;

use frontend\models\User;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {

        $users = User::find()
            ->with('tasks0')
            ->with('feedbacks0')
            ->with('userCategories.categories')
            ->with('profile')
            ->where('exists(SELECT `id` FROM user_category WHERE user.id = user_id)')
            ->orderBy('registration_at DESC')
            ->groupBy('user.id')
            ->all();

        return $this->render('index', compact('users'));
    }

}
