<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $city название города
 * @property string $latitude_y координаты по широте
 * @property string $longitude_x координаты по долготе
 *
 * @property Task[] $tasks
 * @property User[] $users
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city', 'latitude_y', 'longitude_x'], 'required'],
            [['city'], 'string', 'max' => 255],
            [['latitude_y', 'longitude_x'], 'string', 'max' => 24],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city' => 'City',
            'latitude_y' => 'Latitude Y',
            'longitude_x' => 'Longitude X',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['city_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['city_id' => 'id']);
    }

    public static function getListCities()
    {
        $cities = City::find()->all();
        $names = [];
        foreach ($cities as $city) {
            $names[$city->id] = $city->city;
        }
        return $names;
    }
}
