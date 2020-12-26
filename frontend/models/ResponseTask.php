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
}
