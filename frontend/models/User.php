<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name фио пользователя
 * @property string $email email пользователя
 * @property int $city_id id города проживания пользователя
 * @property string|null $registration_at дата и время регистрации
 * @property string $password зашифрованный пароль
 *
 * @property Favorite[] $favorites
 * @property Favorite[] $favorites0
 * @property Feedback[] $feedbacks
 * @property Feedback[] $feedbacks0
 * @property Message[] $messages
 * @property Message[] $messages0
 * @property Profile $profile
 * @property Response[] $responses
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property City $city
 * @property UserAttachment[] $userAttachments
 * @property UserCategory[] $userCategories
 * @property UserPreference[] $userPreferences
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'city_id', 'password'], 'required'],
            [['city_id'], 'integer'],
            [['registration_at'], 'safe'],
            [['name', 'email', 'password'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'city_id' => 'City ID',
            'registration_at' => 'Registration At',
            'password' => 'Password',
        ];
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Favorites0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites0()
    {
        return $this->hasMany(Favorite::class, ['favorite_id' => 'id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Feedbacks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks0()
    {
        return $this->hasMany(Feedback::class, ['executor_id' => 'id']);
    }

    public function getTotalFeedback()
    {
        return count($this->feedbacks0);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Messages0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Message::class, ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Profile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[UserAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAttachments()
    {
        return $this->hasMany(UserAttachment::class, ['user_id' => 'id']);
    }

    public function getTotalTasks()
    {
        return count($this->tasks0);
    }

    public function getTotalTasksForAuthor()
    {
        return count($this->tasks);
    }

    public function getTotalFeedbacks()
    {
        return count($this->feedbacks0);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserPreferences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserPreferences()
    {
        return $this->hasMany(UserPreference::class, ['user_id' => 'id']);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
