<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\SearchTaskForm;
use frontend\models\Task;
use Yii;
use yii\web\Controller;

class TasksController extends Controller
{
    private $timeIntervals = [
        'day' => '-1 day',
        'week' => '-1 week',
        'month' => '-1 month',
        'all' => '0'
    ];
    public function actionIndex()
    {
        $tasks = Task::find()
            ->with('category')
            ->where(['status' => 'new'])
            ->orderBy(['created_at' => SORT_DESC]);

        if (Yii::$app->request->getIsPost()) {
            $search = new SearchTaskForm();
            $search->load(Yii::$app->request->post());
            if ($search->categories) {
                $tasks->andWhere(['category_id' => $search->categories]);
            }
            if ($search->remoteWork) {
                $tasks->andWhere(['city_id' => null]);
                $tasks->andWhere(['address' => '']);
            }
            if ($search->noFeedback) {
                $tasks->joinWith('responses')
                    ->having('COUNT(response.id) = 0')
                    ->groupBy('task.id');
            }
            if ($search->searchByName) {
                $tasks->andFilterWhere(['like', 'task.title', $search->searchByName]);
            }
            $currentTimePeriod = $search->period;
            $startDate = date('Y-m-d H:i', strtotime($this->timeIntervals[$currentTimePeriod] . '00:00:00'));
            $tasks->andWhere(['>', 'task.created_at', $startDate]);
        }

        $tasks = $tasks->limit(5)->all();

        $categories = Category::find()->all();

        $names = [];
        foreach ($categories as $category) {
            $names[$category->id] = $category->name;
        }
        $categories = $names;
        $model = new SearchTaskForm();

        return $this->render('index', compact('tasks', 'model', 'categories'));
    }
}
