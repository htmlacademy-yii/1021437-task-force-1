<?php

namespace frontend\controllers;

use frontend\models\User;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionIndex($id)
    {
        $users = User::findOne($id);
        $profile = $users->profiles;
        return $this->render('index', compact('users', 'profile'));
    }
}
