<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\CompleteTaskForm;
use frontend\models\CreateTaskForm;
use frontend\models\ResponseTask;
use frontend\models\SearchTaskForm;
use frontend\models\Task;
use frontend\models\User;
use Task\classes\exceptions\IncorrectRoleException;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Task\classes\Task as TaskProperties;

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
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задача с таким ID - $id не найдена");
        }

        $executorId = ($task->status === TaskProperties::STATUS_NEW) ? Yii::$app->user->id : $task->executor_id;

        if (!$executorId) {
            throw new ForbiddenHttpException('Задача - '. TaskProperties::MAP_STATUSES_NAME[$task->status]  .'. Доступ закрыт.');
        }

        $modelResponse = new ResponseTask();
        $modelComplete = new CompleteTaskForm();

        $currentTask = new TaskProperties($task->author_id, $executorId, Yii::$app->user->id, $task->status);
        $status = ($task->status === TaskProperties::STATUS_CANCEL) ? 'отменена' : 'провалена';

        try {
            $action = $currentTask->getActionsFromStatus();
        } catch(IncorrectRoleException $e) {
            $message = ($task->status === $currentTask::STATUS_CANCEL || $task->status === $currentTask::STATUS_FAILED) ?
                'Задача - '. $status .'. Доступ закрыт.' :
                'Страница доступна только для исполнителей и заказчиков в режиме - "В работе"';
            throw new ForbiddenHttpException($message);
        }

        $response = $task->getResponseUser(Yii::$app->user->id);
        if (!empty($task->address)) {
            Yii::$app->view->registerJsVar('coordinates', [$task->latitude_y, $task->longitude_x]);
        }

        return $this->render(
            'view',
            compact(
            'task', 'modelResponse',
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
        if (!Yii::$app->request->isPost) {
            throw new NotFoundHttpException('Указана неверная страница');
        }
        $model->load(\Yii::$app->request->post());
        $action = new ActionsWithTask();
        $action->sendResponse($model);
        return $this->redirect(['tasks/view/', 'id' => $model->taskId]);
    }

    public function actionComplete($id)
    {
        $model = new CompleteTaskForm();
        if (!Yii::$app->request->isPost) {
            throw new NotFoundHttpException('Произошла ошибка. Повторите');
        }
        $model->load(Yii::$app->request->post());
        $task = Task::find()->where(['id' => $id, 'author_id' => Yii::$app->user->id])->one();
        if (!$task) {
            throw new NotFoundHttpException('Нет такой задачи');
        }
        $action = new ActionsWithTask();
        if ($action->sendComplete($model, $task, Yii::$app->user->id)) {
            return $this->redirect(['/tasks']);
        }
        return $model->getErrors();
    }

    public function actionFailedTask($id)
    {
        $action = new ActionsWithTask();
        $task = Task::find()->where(['id' => $id])->one();
        if (!$task) {
            throw new NotFoundHttpException('Нет такой задачи');
        }
        $action->sendFailedTask($task);
        return $this->redirect(['/tasks']);
    }

    public function actionCancel($id)
    {
        $task = Task::find()->where(['id' => $id])->one();
        if (!$task) {
            throw new NotFoundHttpException('Нет такой задачи');
        }
        $action = new ActionsWithTask();
        $action->sendCancel($task);
        return $this->redirect(['tasks/view/', 'id' => $id]);
    }

    public function actionRejectResponse($userId, $taskId)
    {
        $action = new ActionsWithTask();
        $action->sendRejectResponse($userId, $taskId);
        return $this->redirect(['tasks/view/', 'id' => $taskId]);
    }

    public function actionAcceptResponse($userId, $taskId)
    {
        $action = new ActionsWithTask();
        $task = Task::find()->where(['id' => $taskId])->one();
        if (!$task) {
            throw new NotFoundHttpException('Нет такой задачи');
        }
        $action->sendAcceptResponse($userId, $task);
        return $this->redirect(['tasks/view/', 'id' =>$taskId]);
    }
}
