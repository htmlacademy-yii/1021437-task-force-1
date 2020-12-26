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
}
