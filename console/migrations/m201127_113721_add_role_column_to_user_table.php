<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m201127_113721_add_role_column_to_user_table extends Migration
{

    private $table = '{{%user}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'role', "enum('client','executor') COLLATE utf8_unicode_ci DEFAULT 'client' COMMENT 'Роль пользователя' AFTER password");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'role');
    }
}
