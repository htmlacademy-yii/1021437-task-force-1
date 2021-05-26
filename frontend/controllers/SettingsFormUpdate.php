<?php


namespace frontend\controllers;


use frontend\models\User;
use frontend\models\UserAttachment;
use frontend\models\UserCategory;
use yii\web\UploadedFile;

class SettingsFormUpdate
{
    public function clearUserCategories($id)
    {
        $items = UserCategory::find()->where(['user_id' => $id])->all();
        foreach ($items as $item) {
            $item->delete();
        }
    }

    private function clearUserPortfolioImage($id, $countFiles)
    {
        $countPhotos = UserAttachment::find()->where(['user_id' => $id])->count();
        if ($countPhotos >= 6) {
            $photos = UserAttachment::find()->where(['user_id' => $id])->limit($countFiles)->all();
            foreach ($photos as $item) {
                $item->delete();
            }
        }
    }

    public function saveProfileImages($files, $user)
    {
        foreach ($files as $file) {
            $fileName = uniqid() . $file->name;
            $name = "uploads/attachments/" . $fileName;
            $file->saveAs($name);

            $attachment = new UserAttachment();
            $attachment->user_id = $user->id;
            $attachment->image_link = '/' . $name;
            $attachment->save();
        }
    }

    public function setRoleClient($user, $role)
    {
        $user->role = $role;
        $user->save();
    }

    public function getRoleUser($user)
    {
        return $user->role;
    }

    public function clearCategoriesForUser($user, $role)
    {
        $this->clearUserCategories($user->id);
        $this->setRoleClient($user, $role);
        return [];
    }

    public function getCategoriesFromProfile($id)
    {
        $categoryFromProfile = UserCategory::find()->where(['user_id' => $id])->asArray()->all();
        $categoryProfileUser = [];
        foreach ($categoryFromProfile as $item) {
            $categoryProfileUser[] = $item['categories_id'];
        }

        return $categoryProfileUser;
    }

    public function getNewCategoriesFromProfile($user, $categories)
    {

        if ($this->getRoleUser($user) === 'client') {
            $this->setRoleClient($user, 'executor');
        }

        $categoryProfileUser = $this->getCategoriesFromProfile($user->id);

        foreach ($categories as $item) {
            $category = UserCategory::find()->where(['user_id' => $user->id])->andWhere(['categories_id' => $item])->asArray()->all();
            if (empty($category)) {
                $newUserCategory = new UserCategory();
                $newUserCategory->user_id = $user->id;
                $newUserCategory->categories_id = $item;
                $newUserCategory->save();
            }
            $categoryProfileUser = array_diff($categoryProfileUser, [$item]);
        }

        if (!empty($categoryProfileUser)) {
            foreach ($categoryProfileUser as $item) {
                UserCategory::deleteAll(['user_id' => $user->id, 'categories_id' => $item]);
            }
        }

        return $this->getCategoriesFromProfile($user->id);
    }

    public function setAvatarUser($model, $user)
    {
        if (UploadedFile::getInstance($model, 'avatar')) {
            $model->avatar = UploadedFile::getInstance($model, 'avatar');
            $fileName = 'uploads/avatars/' . uniqid() . $model->avatar->baseName . '.' . $model->avatar->extension;
            $model->avatar->saveAs($fileName);
            $user->profile->avatar = $fileName;
            $model->avatar = $fileName;
        }
    }

    private function setGeneratePassword($model, $user)
    {
        if ($model->password) {
            $model->password = \Yii::$app->security->generatePasswordHash($model->password);
            $model->password_confirmation = $model->password;
            $user->password = $model->password;
        }
    }

    private function setCategories($model, $user)
    {
        if ($model->categories) {
            $this->getNewCategoriesFromProfile($user, $model->categories);
        } else {
            $this->clearCategoriesForUser($user, 'client');
        }
    }

    public function setImageForProfile($user)
    {
        if (\Yii::$app->request->getIsAjax()) {
            $files = UploadedFile::getInstancesByName('file');
            $countFiles = count($files);
            if ($files) {
                $this->clearUserPortfolioImage($user->id, $countFiles);
                $this->saveProfileImages($files, $user);
            }
        }
    }

    public function saveProfile($user, $model)
    {
        $user->name = $model->name;
        $user->email = $model->email;
        $user->city_id = $model->town;
        $user->profile->birthday_at = $model->dateBirthday;
        $user->profile->user_info = $model->info;
        $user->profile->phone = $model->phone;
        $user->profile->skype = $model->skype;
        $user->profile->telegram = $model->telegram;
        $user->userPreferences[0]->notifications_new_message = $model->notifications_new_message ?? 0;
        $user->userPreferences[0]->notifications_task_actions = $model->notifications_task_actions ?? 0;
        $user->userPreferences[0]->notifications_new_review = $model->notifications_new_review ?? 0;
        $user->userPreferences[0]->public_contacts = $model->public_contacts ?? 0;
        $user->userPreferences[0]->hidden_profile = $model->hidden_profile ?? 0;
        $this->setGeneratePassword($model, $user);
        $this->setCategories($model, $user);
        $this->setAvatarUser($model, $user);

        $user->save();
        $user->profile->save();
        $user->userPreferences[0]->save();

    }

}
