<?php

use yii\db\Migration;

/**
 * Class m201127_085154_update_fields_table_task
 */
class m201127_085154_update_fields_table_task extends Migration
{

    private $table = '{{%task}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->table, 'latitude_y', $this->string('24')->null());
        $this->alterColumn($this->table, 'longitude_x', $this->string('24')->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->table, 'latitude_y', $this->string('24')->notNull());
        $this->alterColumn($this->table, 'longitude_x', $this->string('24')->notNull());
    }

}
