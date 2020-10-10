<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "task_attachment".
 *
 * @property int $id
 * @property int $task_id id задачи
 * @property string $file_link ссылка на файл в задаче
 *
 * @property Task $task
 */
class TaskAttachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_attachment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'file_link'], 'required'],
            [['task_id'], 'integer'],
            [['file_link'], 'string', 'max' => 2048],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'file_link' => 'File Link',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
