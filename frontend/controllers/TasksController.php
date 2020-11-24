<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\SearchTaskForm;
use frontend\models\Task;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends BaseController
{
    public function actionIndex()
    {
        $model = new SearchTaskForm();
        $model->load(Yii::$app->request->get());
        $tasks = $model->getTasks();
        $categories = Category::getNameCategories();

        return $this->render('index', compact('tasks', 'model', 'categories'));
    }

    public function actionView($id)
    {
        $idTask = $id;

        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задача с таким ID - $id не найдена");
        }

        return $this->render('view', compact('idTask', 'task'));
    }
}
