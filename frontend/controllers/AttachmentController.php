<?php

namespace frontend\controllers;

use frontend\models\TaskAttachment;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\UploadedFile;

abstract class AttachmentController extends Controller
{
    public static function saveImage()
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

    public static function attachFiles($files, $idTask)
    {
        foreach ($files as $file) {
            $attachments = new TaskAttachment();
            $attachments->task_id = $idTask;
            $attachments->file_name = $file[0];
            $attachments->file_link = $file[1];
            $attachments->save();
        }
    }

}
