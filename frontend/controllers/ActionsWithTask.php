<?php

namespace frontend\controllers;

use frontend\models\Feedback;
use frontend\models\Profile;
use frontend\models\Response;
use frontend\models\Task;
use Task\classes\Task as TaskProperties;

class ActionsWithTask
{

    private function saveFeedBack($model, $task, $userId, $id, $status)
    {
        $feedback = new Feedback();
        $feedback->author_id = $userId;
        $feedback->executor_id = $task->executor_id;
        $feedback->task_id = $id;
        $feedback->comment = $model->text;
        $feedback->rating = $model->rating;
        $feedback->status = $status;
        $feedback->created_at = date('Y-m-d H:i:s');
        $feedback->save();
    }

    private function updateRating($id)
    {
        $sum = Feedback::find()->where(['executor_id' => $id])->sum('rating');
        $countExecutor = Task::find()->where(['executor_id' => $id])->count();
        $profile = Profile::find()->where(['user_id' => $id])->one();
        $profile->rating = $sum / $countExecutor;
        $profile->save();
    }


    public function sendResponse($model)
    {
        if ($model->validate()) {
            $response = new Response();
            $response->task_id = $model->taskId;
            $response->executor_id = \Yii::$app->user->id;
            $response->budget = $model->budget;
            $response->text_responses = $model->text;
            $response->save();
        }
    }

    public function sendComplete($model, $task, $userId)
    {
        if ($model->validate()) {
            $status = ($model->status === 'yes') ? 'success' : 'failed';
            $task->status = $status;
            $task->ends_at = date("Y-m-d H:i:s");
            $task->save();
            $this->saveFeedBack($model, $task, $userId, $task->id, $status);
            $this->updateRating($task->executor_id);
            return true;
        }
        return false;
    }

    public function sendFailedTask($task)
    {
        $task->status = TaskProperties::STATUS_FAILED;
        $task->ends_at = date("Y-m-d H:i:s");
        $task->save();

        $user = Profile::find()->where(['user_id' => $task->executor_id])->one();
        $user->counter_of_failed_tasks = intval($user->counter_of_failed_tasks) + 1;
        $user->save();
    }

    public function sendCancel($task)
    {
        $task->status = TaskProperties::STATUS_CANCEL;
        $task->ends_at = date("Y-m-d H:i:s");
        return $task->save();
    }

    public function sendRejectResponse($userId, $taskId)
    {
        $response = Response::find()->where(['task_id' => $taskId, 'executor_id' => $userId])->one();
        $response->status_response = TaskProperties::RESPONSE_DISABLE;
        return $response->save();
    }

    public function sendAcceptResponse($userId, $task)
    {
        $response = Response::find()->where(['task_id' => $task->id, 'executor_id' => $userId])->one();
        $response->status_response = TaskProperties::RESPONSE_ACCEPT;
        $response->save();
        $task->status = TaskProperties::STATUS_IN_WORK;
        $task->executor_id = $userId;
        $task->start_at = date("Y-m-d H:i:s");
        return $task->save();
    }
}
