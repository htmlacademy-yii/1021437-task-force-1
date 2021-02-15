<?php

namespace frontend\modules\api\controllers;

use frontend\controllers\CreateNewMessage;
use frontend\models\Message;

use Yii;
use frontend\models\Task;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class MessagesController extends ActiveController
{
    public $modelClass = Message::class;

    public function actionIndex()
    {
        if (!$id = \Yii::$app->request->get('task_id')) {
            return false;
        }
        return Message::find()->where(['task_id' => $id])->all();
    }

    public function actions()
    {

        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'actionIndex'];
        unset($actions['create']);

        return $actions;
    }

    public function actionCreate()
    {
        Yii::$app->response->setStatusCode(201);
        $content = json_decode(Yii::$app->getRequest()->getRawBody(), true);

        $task = Task::findOne($content['taskId']);

        if (!$task && $task->status === \Task\classes\Task::STATUS_IN_WORK) {
            throw new ForbiddenHttpException();
        }

        $message = new CreateNewMessage();
        $message->saveMessage($task, $content['message']);

        return $this->asJson([
            'author_id' => $task->author_id,
            'message' => $content['message'],
            'recipient_id' => $task->executor_id,
            'task_id' => $task->id,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
