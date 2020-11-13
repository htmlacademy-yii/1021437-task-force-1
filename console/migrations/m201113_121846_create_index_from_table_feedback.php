<?php

use yii\db\Migration;

/**
 * Class m201113_121846_create_index_from_table_feedback
 */
class m201113_121846_create_index_from_table_feedback extends Migration
{

    private $table = '{{%feedback}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('task_id2', $this->table, 'task_id', $unique = true);
        $this->dropIndex('task_id', $this->table);
        $this->createIndex('task_id', $this->table, 'task_id', $unique = true);
        $this->dropIndex('task_id2', $this->table);
    }

}
