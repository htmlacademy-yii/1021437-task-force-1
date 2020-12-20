<?php


namespace frontend\models;

use yii\base\Model;

class ResponseTask extends Model
{

    public $budget;
    public $text;
    public $taskId;

    public function rules()
    {
        return [
            [['budget', 'text', 'taskId'], 'safe'],
            ['budget', 'integer', 'min' => '1', 'tooSmall' => 'Значение должно быть целым положительным числом'],
            ['taskId', 'exist', 'targetClass' => Task::class, 'targetAttribute' => 'id', 'message' => 'Не существует такой задачи'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'budget' => 'Ваша цена',
            'text' => 'Комментарий'
        ];
    }

    public function saveResponse($model)
    {
        $response = new Response();
        $response->task_id = $model->taskId;
        $response->executor_id = \Yii::$app->user->id;
        $response->budget = $model->budget;
        $response->text_responses = $model->text;
        $response->save();
    }

}
