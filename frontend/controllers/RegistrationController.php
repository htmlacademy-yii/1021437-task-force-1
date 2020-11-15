<?php


namespace frontend\controllers;

use frontend\models\City;
use frontend\models\RegistrationForm;
use frontend\models\User;
use Yii;
use yii\web\Controller;

class RegistrationController extends Controller
{

    public function actionIndex()
    {
        $model = new RegistrationForm();
        $cities = City::getListCities();
        $errors = [];
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $user = new User();
                $user->name = $model->name;
                $user->email = $model->email;
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
                $user->city_id = $model->city;
                if ($user->save()) {
                    return $this->goHome();
                }
            }
            $errors = $model->getErrors();
        }

        return $this->render('index', compact('model', 'cities', 'errors'));
    }
}
