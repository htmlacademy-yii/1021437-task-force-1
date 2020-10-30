<?php

namespace frontend\controllers;

use frontend\models\SearchTaskForm;
use Yii;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $model = new SearchTaskForm();
        $model->load(Yii::$app->request->get());
        $tasks = $model->getTasks();
        $categories = $model->getCategories();

        return $this->render('index', compact('tasks', 'model', 'categories'));
    }
}
