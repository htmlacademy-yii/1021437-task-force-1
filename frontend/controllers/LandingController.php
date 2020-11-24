<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LandingController extends Controller
{
    public $layout = 'landing';

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/tasks']);
        }

        $title = 'Task-Force: Главная страница';
        $model = new LoginForm();
        $errors = [];

        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->validate()) {
                $user = $model->getUser();
                Yii::$app->user->login($user);
                return $this->redirect(['tasks/']);
            }
            $errors = $model->getErrors();
        }
        return $this->render('index', compact('model', 'errors', 'title'));
    }
}
