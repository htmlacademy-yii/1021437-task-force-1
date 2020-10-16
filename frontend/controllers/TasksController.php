<?php

namespace frontend\controllers;

use frontend\models\Task;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
            ->with('category')
            ->where(['status' => 'new'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('index', compact('tasks'));
    }
}
