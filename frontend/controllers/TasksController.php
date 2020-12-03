<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\CreateTaskForm;
use frontend\models\SearchTaskForm;
use frontend\models\Task;
use frontend\models\User;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
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

    public function actionCreate()
    {

        if (Yii::$app->user->getIdentity()->role !== User::CLIENT) {
            throw new ForbiddenHttpException('Страница доступна только для заказчиков');
        }

        $model = new CreateTaskForm();
        $categories = Category::getNameCategories();
        $errors = [];

        $service = new ProcessingFormCreateTask();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($id = $service->saveTask($model)) {
                $this->redirect(['/tasks/view', 'id' => $id]);
            }

            $errors= $model->getErrors();
        }

        return $this->render('create', compact('model', 'categories', 'errors'));
    }
}
