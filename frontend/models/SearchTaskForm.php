<?php

namespace frontend\models;
use yii\db\ActiveRecord;

class SearchTaskForm extends ActiveRecord
{
    public $categories;
    public $noFeedback = false;
    public $remoteWork = false;
    public $searchByName;
    public $period = [
        'day' => 'За день',
        'week' => 'За неделю',
        'month' => 'За месяц',
        'all' => 'За всё время'
    ];
    public $defaultPeriod = 'week';

    public function rules()
    {
        return [
            [['categories', 'noFeedback', 'remoteWork', 'period', 'searchByName'], 'safe'],
            [['searchByName'], 'string', 'max' => 255],
            [['noFeedback', 'remoteWork'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'noFeedback' => 'Без откликов',
            'remoteWork' => 'Удаленная работа',
            'searchByName' => 'Поиск по названию',
        ];
    }

}
