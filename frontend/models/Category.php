<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name имя категории
 * @property string $category_icon иконка категории
 *
 * @property Task[] $tasks
 * @property UserCategory[] $userCategories
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'category_icon'], 'required'],
            [['name'], 'string', 'max' => 128],
            [['category_icon'], 'string', 'max' => 255],
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
            'category_icon' => 'Category Icon',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['category_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::className(), ['categories_id' => 'id']);
    }

    public static function getNameCategories()
    {
        $categories = Category::find()->all();
        $names = [];
        foreach ($categories as $category) {
            $names[$category->id] = $category->name;
        }
        return $names;
    }
}
