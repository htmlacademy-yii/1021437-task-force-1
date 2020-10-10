<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property int $user_id id пользователя
 * @property string|null $address адресс проживания
 * @property string|null $birthday_at дата рождения
 * @property string|null $user_info контактная информация
 * @property float|null $rating рейтинг пользователя
 * @property int $views_account количество просмотров профиля пользователя
 * @property string|null $avatar аватар пользователя
 * @property string|null $phone телефон пользователя
 * @property string|null $skype skype пользователя
 * @property string|null $telegram telegram пользователя
 * @property string|null $last_visit дата и время последней активности
 * @property int|null $counter_of_failed_tasks количество проваленных заданий
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'views_account', 'counter_of_failed_tasks'], 'integer'],
            [['address', 'user_info'], 'string'],
            [['birthday_at', 'last_visit'], 'safe'],
            [['rating'], 'number'],
            [['avatar'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['skype', 'telegram'], 'string', 'max' => 128],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'address' => 'Address',
            'birthday_at' => 'Birthday At',
            'user_info' => 'User Info',
            'rating' => 'Rating',
            'views_account' => 'Views Account',
            'avatar' => 'Avatar',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'telegram' => 'Telegram',
            'last_visit' => 'Last Visit',
            'counter_of_failed_tasks' => 'Counter Of Failed Tasks',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
