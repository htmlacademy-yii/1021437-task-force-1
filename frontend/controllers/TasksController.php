<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\CompleteTaskForm;
use frontend\models\CreateTaskForm;
use frontend\models\Profile;
use frontend\models\Response;
use frontend\models\ResponseTask;
use frontend\models\SearchTaskForm;
use frontend\models\Task;
use frontend\models\User;
use Exception;
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

        $modelResponse = new ResponseTask();
        $modelComplete = new CompleteTaskForm();

        $response = $task->getResponseUser($task->id, Yii::$app->user->id);

        $executorId = ($task->status === 'new') ? Yii::$app->user->id : $task->executor_id;
        $currentTask = new \Task\classes\Task($task->author_id, $executorId, Yii::$app->user->id, $task->status);

        $status = ($task->status === 'canceled') ? 'отменена' : 'провелена';

        try {
            $action = $currentTask->getActionsFromStatus();
        } catch(Exception $e) {
            $message = ($task->status === $currentTask::STATUS_CANCEL || $task->status === $currentTask::STATUS_FAILED) ?
                'Задача - '. $status .'. Доступ закрыт.' :
                'Страница доступна только для исполнителей и заказчиков в режиме - "В работе"';
            throw new ForbiddenHttpException($message);
        }

        return $this->render(
            'view',
            compact(
            'idTask', 'task', 'modelResponse',
            'modelComplete', 'response', 'executorId',
            'currentTask', 'action'
            )
        );
    }

    public function actionCreate()
    {
        if (Yii::$app->user->getIdentity()->role !== User::CLIENT) {
            throw new ForbiddenHttpException('Страница доступна только для заказчиков');
        }

        $model = new CreateTaskForm();
        $categories = Category::getNameCategories();
        $service = new ProcessingFormCreateTask();
        $errors = [];

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($id = $service->saveTask($model)) {
                $this->redirect(['/tasks/view', 'id' => $id]);
            }
            $errors= $model->getErrors();
        }

        return $this->render('create', compact('model', 'categories', 'errors'));
    }

    public function actionRespond()
    {
        $model = new ResponseTask();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $model->saveResponse($model);
                return $this->redirect(['tasks/view/', 'id' => $model->taskId]);
            }
        }
        throw new NotFoundHttpException('Указана неверная страница');
    }

    public function actionComplete($id)
    {
        $model = new CompleteTaskForm();
        $userId = Yii::$app->user->id;
        $errors = [];
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $status = ($model->status === 'yes') ? 'success' : 'failed';
            if ($model->validate()) {
                $task = Task::find()->where(['id' => $id, 'author_id' => $userId])->one();
                $task->status = $status;
                $task->ends_at = date("Y-m-d H:i:s");
                $task->save();

                $model->saveFeedBack($model, $task, $userId, $id, $status);
                $model->updateRating($task->executor_id);

                return $this->redirect(['/tasks']);
            }
            $errors = $model->getErrors();
        }
        return $errors;
    }

    public function actionFailedTask($id)
    {
        $task = Task::find()->where(['id' => $id])->one();
        $task->status = 'failed';
        $task->ends_at = date("Y-m-d H:i:s");
        $task->save();

        $user = Profile::find()->where(['user_id' => Yii::$app->user->id])->one();
        $user->counter_of_failed_tasks = intval($user->counter_of_failed_tasks) + 1;
        $user->save();
        return $this->redirect(['/tasks']);
    }

    public function actionCancel($id)
    {
        $task = Task::find()->where(['id' => $id])->one();
        $task->status = 'canceled';
        $task->ends_at = date("Y-m-d H:i:s");
        if ($task->save()) {
            return $this->redirect(['tasks/view/', 'id' => $id]);
        }
    }

    public function actionRejectResponse($userId, $taskId)
    {
        $response = Response::find()->where(['task_id' => $taskId, 'executor_id' => $userId])->one();
        $response->status_response = 'disable';
        $response->save();
        return $this->redirect(['tasks/view/', 'id' => $taskId]);
    }

    public function actionAcceptResponse($userId, $taskId)
    {
        $response = Response::find()->where(['task_id' => $taskId, 'executor_id' => $userId])->one();
        $response->status_response = 'accept';
        $response->save();

        $task = Task::find()->where(['id' => $taskId])->one();
        $task->status = 'in_work';
        $task->executor_id = $userId;
        $task->start_at = date("Y-m-d H:i:s");

        $task->save();
        return $this->redirect(['tasks/view/', 'id' => $taskId]);
    }
}
