<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_preference".
 *
 * @property int $id
 * @property int $user_id id пользователя
 * @property int|null $notifications_new_message уведомление о новом сообщении
 * @property int|null $notifications_task_actions уведомление о действии по заданию
 * @property int|null $notifications_new_review уведомление о новом отзыве
 * @property int|null $public_contacts скрыть показ контактов
 * @property int|null $hidden_profile скрыть профиль со страницы «Список исполнителей»
 *
 * @property User $user
 */
class UserPreference extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_preference';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'notifications_new_message', 'notifications_task_actions', 'notifications_new_review', 'public_contacts', 'hidden_profile'], 'integer'],
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
            'notifications_new_message' => 'Notifications New Message',
            'notifications_task_actions' => 'Notifications Task Actions',
            'notifications_new_review' => 'Notifications New Review',
            'public_contacts' => 'Public Contacts',
            'hidden_profile' => 'Hidden Profile',
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
