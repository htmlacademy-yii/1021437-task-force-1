<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\CreateTaskForm;
use frontend\models\SearchTaskForm;
use frontend\models\Task;
use frontend\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
{

    public function behaviors()
    {

        $rules = [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@']
//                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            $id = Yii::$app->user->id;
                            $userRole = User::findOne($id);

                            return $userRole->role === User::CLIENT;
                        }
                    ]
                ]
            ]
        ];

        return $rules;
    }

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
