<?php


namespace frontend\controllers;
use frontend\models\Task;
use yii\web\Controller;

class MyListController extends Controller
{
    public function actionIndex()
    {

        $status = \Yii::$app->request->get('status');
        $tasks = Task::find()
            ->where(['author_id' => \Yii::$app->user->getId()])
            ->andWhere(['status' => $status ?? 'new'])->all();

        return $this->render('index', compact('tasks'));
    }

}
