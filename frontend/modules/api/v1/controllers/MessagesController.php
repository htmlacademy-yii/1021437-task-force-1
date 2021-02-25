<?php

namespace frontend\modules\api\v1\controllers;

use frontend\controllers\CreateNewMessage;
use frontend\models\Message;
use Yii;
use frontend\models\Task;
use yii\rest\ActiveController;

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

    public function actionCreate($task_id)
    {
        $content = json_decode(Yii::$app->getRequest()->getRawBody(), true);
        $task = Task::findOne($task_id);

        Yii::$app->response->setStatusCode(201);
        $message = new CreateNewMessage();

        return $this->asJson($message->saveMessage($task, $content['message']));
    }
}
