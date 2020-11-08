<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\SearchUsersForm;
use frontend\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UsersController extends Controller
{
    public function actionIndex($sort = '')
    {

        $model = new SearchUsersForm();
        $categories = Category::getNameCategories();
        $model->load(\Yii::$app->request->get());
        $users = $model->getUsers($sort);

        return $this->render('index', compact('users', 'model', 'categories'));
    }

    public function actionView($id)
    {
        $user = User::findOne($id);
        if(!$user) {
            throw new NotFoundHttpException("Пользователь с ID - $id не найден");
        }
        return $this->render('view', compact('user'));
    }

}
