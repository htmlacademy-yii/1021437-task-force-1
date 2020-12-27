<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%response}}`.
 */
class m201215_184032_add_status_response_column_to_response_table extends Migration
{

    private $table = '{{%response}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'status_response', "enum('accept', 'disable', 'new') COLLATE utf8_unicode_ci DEFAULT 'new' COMMENT 'Статус отклика' AFTER text_responses");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'status_response');
    }
}
