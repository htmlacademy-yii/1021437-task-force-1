<?php

namespace frontend\controllers;

use frontend\models\Task;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
            ->where(['status' => 'new'])
            ->with('category')
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('index', compact('tasks'));
    }
}
