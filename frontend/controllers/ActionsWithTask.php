<?php


namespace frontend\controllers;

use frontend\models\Profile;
use frontend\models\Response;
use frontend\models\Task;
use yii\web\Controller;

class ActionsWithTask
{

    public function sendResponse($model)
    {
        $model->load(\Yii::$app->request->post());
        if ($model->validate()) {
            return $model->saveResponse($model);
        }
    }

    public function sendComplete($model, $id, $userId)
    {
        $task = Task::find()->where(['id' => $id, 'author_id' => $userId])->one();
        $status = ($model->status === 'yes') ? 'success' : 'failed';
        $task->status = $status;
        $task->ends_at = date("Y-m-d H:i:s");
        $task->save();

        $model->saveFeedBack($model, $task, $userId, $id, $status);
        $model->updateRating($task->executor_id);
    }

    public function sendFailedTask($id)
    {
        $task = Task::find()->where(['id' => $id])->one();
        $task->status = 'failed';
        $task->ends_at = date("Y-m-d H:i:s");
        $task->save();

        $user = Profile::find()->where(['user_id' => Yii::$app->user->id])->one();
        $user->counter_of_failed_tasks = intval($user->counter_of_failed_tasks) + 1;
        $user->save();
    }

    public function sendCancel($id)
    {
        $task = Task::find()->where(['id' => $id])->one();
        $task->status = 'canceled';
        $task->ends_at = date("Y-m-d H:i:s");
        return $task->save();
    }

    public function sendRejectResponse($userId, $taskId)
    {
        $response = Response::find()->where(['task_id' => $taskId, 'executor_id' => $userId])->one();
        $response->status_response = 'disable';
        return $response->save();
    }

    public function sendAcceptResponse($userId, $taskId)
    {
        $response = Response::find()->where(['task_id' => $taskId, 'executor_id' => $userId])->one();
        $response->status_response = 'accept';
        $response->save();

        $task = Task::find()->where(['id' => $taskId])->one();
        $task->status = 'in_work';
        $task->executor_id = $userId;
        $task->start_at = date("Y-m-d H:i:s");
        $task->save();
    }
}
