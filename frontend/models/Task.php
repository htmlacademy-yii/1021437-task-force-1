<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title название задачи
 * @property string $description описание лота
 * @property int $category_id id категории объявления
 * @property string|null $status статус задачи
 * @property int|null $budget бюджет
 * @property string|null $address адресс выполнения задачи
 * @property string|null $created_at дата и время создания задачи
 * @property string|null $start_at дата и время начала выполнения задачи
 * @property int|null $city_id id города
 * @property string $latitude_y координаты по широте к области выполнения задания
 * @property string $longitude_x координаты по долготе к области выполнения задания
 * @property int $author_id id автора
 * @property int|null $executor_id id исполнителя
 * @property string|null $ends_at крайний срок исполнения задания
 *
 * @property Feedback[] $feedbacks
 * @property Message[] $messages
 * @property Response[] $responses
 * @property Category $category
 * @property City $city
 * @property User $author
 * @property User $executor
 * @property TaskAttachment[] $taskAttachments
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'category_id', 'latitude_y', 'longitude_x', 'author_id'], 'required'],
            [['description', 'status', 'address'], 'string'],
            [['category_id', 'budget', 'city_id', 'author_id', 'executor_id'], 'integer'],
            [['created_at', 'start_at', 'ends_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['latitude_y', 'longitude_x'], 'string', 'max' => 24],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['executor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'status' => 'Status',
            'budget' => 'Budget',
            'address' => 'Address',
            'created_at' => 'Created At',
            'start_at' => 'Start At',
            'city_id' => 'City ID',
            'latitude_y' => 'Latitude Y',
            'longitude_x' => 'Longitude X',
            'author_id' => 'Author ID',
            'executor_id' => 'Executor ID',
            'ends_at' => 'Ends At',
        ];
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[TaskAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskAttachments()
    {
        return $this->hasMany(TaskAttachment::className(), ['task_id' => 'id']);
    }
}
