<?php

namespace frontend\models;

use yii\base\Model;

class RegistrationForm extends Model
{
    public $email;
    public $name;
    public $password;
    public $errors;
    public $city;

    public function rules()
    {
        return [
            [['email', 'name', 'city', 'password'], 'required'],
            [['email', 'name'], 'trim'],
            ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Эта почта уже зарегистрирована'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['password', 'string', 'min' => 8, 'max' => 255, 'message' => 'Длина пароля от 8 символов'],
            ['city', 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'name' => 'Ваше имя',
            'city' => 'Город проживания',
            'password' => 'Пароль',
        ];
    }
}
