<?php

namespace frontend\controllers;

use frontend\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function getProfile()
    {
        if ($id = \Yii::$app->user->getId()) {
            $user = User::findOne($id);
            return $user->name;
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

}
