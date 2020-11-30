<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

class SearchTaskForm extends ActiveRecord
{
    public $categories;
    public $noFeedback = false;
    public $remoteWork = false;
    public $searchByName;
    public $defaultPeriod = 'week';
    public $periods = [
        'day' => 'За день',
        'week' => 'За неделю',
        'month' => 'За месяц',
        'all' => 'За всё время'
    ];

    public function rules()
    {
        return [
            [['categories', 'noFeedback', 'remoteWork', 'defaultPeriod', 'searchByName'], 'safe'],
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
            'defaultPeriod' => 'Период',
        ];
    }

    public function getTasks()
    {
        $tasks = Task::find()
            ->with('category')
            ->where(['status' => 'new'])
            ->orderBy(['created_at' => SORT_DESC]);

        $timeIntervals = [
            'day' => '-1 day',
            'week' => '-1 week',
            'month' => '-1 month',
            'all' => '0'
        ];

        if ($this->categories) {
            $tasks->andWhere(['category_id' => $this->categories]);
        }
        if ($this->remoteWork) {
            $tasks->andWhere(['city_id' => null]);
            $tasks->andWhere(['address' => '']);
        }
        if ($this->noFeedback) {
            $tasks->joinWith('responses')
                ->having('COUNT(response.id) = 0')
                ->groupBy('task.id');
        }
        if ($this->searchByName) {
            $tasks->andWhere(new Expression('task.title LIKE :userName', [':userName' => '%' . $this->searchByName . '%']));
        }
        if ($this->defaultPeriod) {
            $currentTimePeriod = $this->defaultPeriod;
            $startDate = date('Y-m-d H:i', strtotime($timeIntervals[$currentTimePeriod] . '00:00:00'));
            $tasks->andWhere(['>', 'task.created_at', $startDate]);
        }

        return $tasks->limit(5)->all();
    }

}
