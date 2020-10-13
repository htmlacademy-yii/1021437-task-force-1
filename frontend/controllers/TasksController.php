<?php

namespace frontend\controllers;

use yii\db\Query;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $query = new Query();
        $query->select(
            [
                't.title',
                't.description',
                't.budget',
                't.address',
                'c.name',
                'c.category_icon',
                't.created_at'
            ])
            ->from('task t')
            ->where(['status' => 'new'])
            ->join('INNER JOIN', 'category c', 't.category_id = c.id')
            ->orderBy(['t.created_at' => SORT_DESC]);
        $tasks = $query->all();
        return $this->render('index', ['tasks' => $tasks]);
    }
}
