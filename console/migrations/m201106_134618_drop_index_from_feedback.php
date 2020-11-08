<?php

use yii\db\Migration;

/**
 * Class m201106_134618_drop_index_from_feedback
 */
class m201106_134618_drop_index_from_feedback extends Migration
{

    private $table = '{{%feedback}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `1021437-task-force-1`.`feedback` DROP INDEX `task_id`, ADD UNIQUE `task_id` (`task_id`) USING BTREE");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `1021437-task-force-1`.`feedback` DROP INDEX `task_id`, ADD INDEX `task_id` (`task_id`) USING BTREE");
    }

}
