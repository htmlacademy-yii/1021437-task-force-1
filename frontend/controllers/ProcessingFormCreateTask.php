<?php

namespace frontend\controllers;

use frontend\models\Task;
use frontend\models\TaskAttachment;
use Yii;
use yii\base\Exception;
use yii\web\UploadedFile;

class ProcessingFormCreateTask
{
    private function saveImage()
    {
        if ($file = UploadedFile::getInstanceByName('Attach')) {
            $fileName = uniqid() . $file->name;
            $filePath = 'uploads/' . $fileName;
            if ($file->saveAs($filePath, false)) {
                $session = Yii::$app->session['imageFile'] ?? [];
                $session[] = [$fileName, $filePath];
                Yii::$app->session['imageFile'] = $session;
            } else {
                throw new Exception('Ошибка сохранения файла');
            }
        }
    }

    private function attachFiles($files, $idTask)
    {
        foreach ($files as $file) {
            $attachments = new TaskAttachment();
            $attachments->task_id = $idTask;
            $attachments->file_name = $file[0];
            $attachments->file_link = $file[1];
            $attachments->save();
        }
    }

    public function saveTask($form)
    {

        $this->saveImage();

        if ($form->validate()) {
            $task = new Task();
            $task->title = $form->title;
            $task->description = $form->description;
            $task->budget = $form->budget;
            $task->author_id = Yii::$app->user->id;
            $task->ends_at = $form->ends_at;
            $task->category_id = $form->category;
            $task->latitude_y = $form->latitude;
            $task->longitude_x = $form->longitude;
            $task->address = $form->location;
            $task->city_id = $form->cityId;
            $task->save();

            if (Yii::$app->session['imageFile']) {
                $this->attachFiles(Yii::$app->session['imageFile'], $task->id);
                $session = Yii::$app->session;
                unset($session['imageFile']);
            }
        }
        return $task->id ?? null;
    }

}
