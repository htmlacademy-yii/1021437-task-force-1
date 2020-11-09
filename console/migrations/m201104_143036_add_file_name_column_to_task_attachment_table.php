<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%task_attachment}}`.
 */
class m201104_143036_add_file_name_column_to_task_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task_attachment}}', 'file_name', $this->string(255)->after('task_id')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%task_attachment}}', 'file_name');
    }
}
