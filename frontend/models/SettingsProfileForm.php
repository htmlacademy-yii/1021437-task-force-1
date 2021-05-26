<?php

namespace frontend\models;
use yii\base\Model;

class SettingsProfileForm extends Model
{
    public $name;
    public $email;
    public $town;
    public $dateBirthday;
    public $info;
    public $avatar;
    public $phone;
    public $skype;
    public $telegram;
    public $notifications_new_message;
    public $notifications_task_actions;
    public $notifications_new_review;
    public $public_contacts;
    public $hidden_profile;
    public $categories;
    public $password;
    public $password_confirmation;
    public $file;

    public function init()
    {
        $user = User::findOne(\Yii::$app->user->getId());
        $this->avatar = $user->profile->avatar;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->dateBirthday = $user->profile->birthday_at;
        $this->info = $user->profile->user_info;
        $this->phone = $user->profile->phone;
        $this->skype = $user->profile->skype;
        $this->telegram = $user->profile->telegram;
        $this->town = $user->city_id;
        $this->notifications_new_message = $user->userPreferences[0]->notifications_new_message ?? '';
        $this->notifications_task_actions = $user->userPreferences[0]['notifications_task_actions']  ?? '';
        $this->notifications_new_review = $user->userPreferences[0]['notifications_new_review']  ?? '';
        $this->public_contacts = $user->userPreferences[0]['public_contacts']  ?? '';
        $this->hidden_profile = $user->userPreferences[0]['hidden_profile']  ?? '';
        $this->categories = $user->getIdCategoriesUser();
    }

    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            ['name', 'string', 'min' => 3, 'max' => 255],
            ['info', 'string'],
            ['dateBirthday', 'date', 'format' => 'yyyy-mm-dd'],
            [
                [
                    'notifications_new_message',
                    'notifications_task_actions',
                    'notifications_new_review',
                    'public_contacts',
                    'hidden_profile'
                ],
                'safe'
            ],
            ['skype', 'string', 'min' => 3, 'max' => 128],
            ['phone', 'string', 'min' => 11, 'max' => 11],
            ['telegram', 'string', 'max' => 128],
            ['password', 'string', 'min' => 3],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 6],
            ['password_confirmation', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли должны совпадать', 'skipOnEmpty' => false],
            ['avatar', 'file', 'extensions' => 'jpg, jpeg'],
            [['categories'], 'validationCategories'],
            ['town', 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id']
        ];
    }

    public function validationCategories($attribute, $params)
    {
        if (empty($this->$attribute)) {
            return true;
        }
        foreach ($this->$attribute as $item) {
            $category = Category::findOne($item);
            if ($category === null) {
                $this->addError($attribute, 'Категории  - ' . $item . ' не существует');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'town' => 'Город',
            'dateBirthday' => 'День рождения',
            'info' => 'Информация о себе',
            'avatar' => 'Сменить аватар',
            'notifications_new_message' => 'Новое сообщение',
            'notifications_task_actions' => 'Действия по заданию',
            'notifications_new_review' => 'Новый отзыв',
            'hidden_profile' => 'Не показывать мой профиль',
            'public_contacts' => 'Показывать мои контакты только заказчику',
            'skype' => 'Skype',
            'telegram' => 'Telegram',
            'phone' => 'Phone',
            'password' => 'Новый пароль',
            'password_confirmation' => 'Повтор пароля',
        ];
    }


}
