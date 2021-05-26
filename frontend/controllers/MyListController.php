<?php


namespace frontend\controllers;
use frontend\models\Task;

class MyListController extends SecuredController
{
    public function actionIndex()
    {
        $status = \Yii::$app->request->get('status');
        $tasks = Task::find()
            ->where(['author_id' => \Yii::$app->user->getId()])
            ->orWhere(['executor_id' => \Yii::$app->user->getId()])
            ->andWhere(['status' => $status ?? 'new'])->all();

        return $this->render('index', compact('tasks'));
    }

}
