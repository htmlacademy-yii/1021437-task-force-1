<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\SearchUsersForm;
use frontend\models\User;
use yii\db\Expression;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex($sort = '')
    {

        $model = new SearchUsersForm();
        $categories = $model->getCategories();
        $model->load(\Yii::$app->request->get());
        $users = $model->getUsers($sort);

        return $this->render('index', compact('users', 'model', 'categories'));
    }

}
