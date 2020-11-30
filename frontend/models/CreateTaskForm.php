<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class CreateTaskForm extends Model
{

    public $title;
    public $description;
    public $category;
    public $budget;
    public $ends_at;

    public function attributeLabels()
    {
        return [
            'title' => 'Мне нужно',
            'description' => 'Подробности задания',
            'category' => 'Категория',
            'budget' => 'Бюджет',
            'ends_at' => 'Срок исполнения',
            'imageFile' => 'Файлы'
        ];
    }

    public function rules()
    {
        return [
            [['title', 'description', 'category'], 'required'],
            [['title', 'description'], 'trim'],
            ['title', 'string', 'min' => 10, 'max' => 255],
            ['description', 'string', 'min' => 30],
            ['category', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id', 'message' => 'Выбранной категории не существует'],
            ['budget', 'integer', 'min' => '1', 'tooSmall' => 'Значение должно быть целым положительным числом'],
            ['ends_at', 'date', 'format' => 'Y-m-d'],
            ['ends_at', 'checkValidateDate'],
        ];
    }

    public function checkValidateDate($attribute, $params)
    {
        if (strtotime($this->$attribute) < strtotime('tomorrow')) {
            $this->addError($attribute, 'Дата окончания не может быть меньше даты начала задачи');
        }
    }

    private function saveImage()
    {
        if ($file = UploadedFile::getInstanceByName('Attach')) {
            $fileName = uniqid() . $file->name;
            $filePath = 'uploads/' . $fileName;
            $file->saveAs($filePath, false);
            $session = Yii::$app->session['imageFile'];
            $session[] = [$fileName, $filePath];
            Yii::$app->session['imageFile'] = $session;
        }
    }

    private function uploadFile($files, $idTask)
    {

        foreach ($files as $file) {
            $attachments = new TaskAttachment();
            $attachments->task_id = $idTask;
            $attachments->file_name = $file[0];
            $attachments->file_link = $file[1];
            $attachments->save();
        }
    }

    public function saveTask()
    {
        $this->saveImage();

        if ($this->validate()) {
            $task = new Task();
            $task->title = $this->title;
            $task->description = $this->description;
            $task->budget = $this->budget;
            $task->author_id = Yii::$app->user->id;
            $task->ends_at = $this->ends_at;
            $task->category_id = $this->category;
            $task->save();

            if (Yii::$app->session['imageFile']) {
                $this->uploadFile(Yii::$app->session['imageFile'], $task->id);

                $session = Yii::$app->session;
                unset($session['imageFile']);
            }
        }
        return $task->id ?? null;
    }
}
