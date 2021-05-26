<?php

use yii\db\Migration;

/**
 * Class m210301_192753_update_field_birthday_from_table_profile
 */
class m210301_192753_update_field_birthday_from_table_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    private $table = '{{%profile}}';


    public function safeUp()
    {
        $this->alterColumn($this->table, 'birthday_at', 'date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->table, 'birthday_at', 'datetime');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210301_192753_update_field_birthday_from_table_profile cannot be reverted.\n";

        return false;
    }
    */
}
