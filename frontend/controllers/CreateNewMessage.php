<?php


namespace frontend\controllers;
use frontend\models\Message;

class CreateNewMessage
{
    public function saveMessage($task, $content)
    {
        $message = new Message();
        $message->author_id = $task->author_id;
        $message->recipient_id = $task->executor_id;
        $message->task_id = $task->id;
        $message->message = $content;
        $message->created_at = date("Y-m-d H:i:s");

        return $message->save();
    }

}
