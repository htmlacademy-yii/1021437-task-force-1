<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\City;
use frontend\models\Profile;
use frontend\models\SearchUsersForm;
use frontend\models\SettingsProfileForm;
use frontend\models\User;
use frontend\models\UserAttachment;
use frontend\models\UserCategory;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class UsersController extends SecuredController
{

    public function actionIndex($sort = '')
    {
        $model = new SearchUsersForm();
        $categories = Category::getNameCategories();
        $model->load(\Yii::$app->request->get());
        $users = $model->getUsers($sort);

        return $this->render('index', compact('users', 'model', 'categories'));
    }

    public function actionView($id)
    {
        $user = User::findOne($id);
        if(!$user) {
            throw new NotFoundHttpException("Пользователь с ID - $id не найден");
        }
        return $this->render('view', compact('user'));
    }

    public function actionSettings($id)
    {
        $model = new SettingsProfileForm();
        $user = User::findOne($id);
        $cities = City::getListCities();
        $categories = Category::getNameCategories();
        $currentCategories = UserCategory::getUserCategories($user);

        $service = new SettingsFormUpdate();
        $service->setImageForProfile($user);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $service->saveProfile($user, $model);
            }
            $errors = $model->getErrors();
        } else {
            $service->getStockValueFromProfile($model, $user, $currentCategories);
        }

        return $this->render('settings', compact('model', 'errors', 'cities', 'categories'));
    }
}
