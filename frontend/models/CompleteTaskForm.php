<?php


namespace frontend\models;

use yii\base\Model;

class CompleteTaskForm extends Model
{

    public $text;
    public $status;
    public $statusValue = [
        'yes' => 'Да',
        'difficult' => 'Возникли проблемы'
    ];
    public $rating;

    public function attributeLabels()
    {
        return [
            'text' => 'Комментарий',
            'rating' => 'Оценка',
            'status' => 'Статус выполнения задания'
        ];
    }

    public function rules()
    {
        return [
            [['status', 'rating', 'text'], 'required'],
            ['text', 'string'],
            ['rating', 'integer', 'min' => '1', 'max' => '5', 'tooSmall' => 'Значение должно быть целым положительным числом, от 1 до 5'],

        ];
    }

    public function saveFeedBack($model, $task, $userId, $id, $status)
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

    public function updateRating($id)
    {
        $sum = Feedback::find()->where(['executor_id' => $id])->sum('rating');
        $countExecutor = Task::find()->where(['executor_id' => $id])->count();
        $profile = Profile::find()->where(['user_id' => $id])->one();
        $profile->rating = $sum / $countExecutor;
        $profile->save();
    }

}
