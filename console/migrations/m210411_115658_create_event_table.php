<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m210411_115658_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'notification_id' => $this->integer()->NotNull()->comment('Тип уведомления'),
            'title' => $this->string(255)->notNull()->comment('Текст уведомления'),
            'task_id' => $this->integer()->NotNull()->comment('Id задачи'),
            'user_id' => $this->integer()->NotNull()->comment('Id получателя'),
            'status' => $this->boolean()->Null()->comment('Статус')
        ]);

        $this->createIndex(
            'event_user',
            'event',
            'user_id'
        );

        $this->addForeignKey(
            'fk-event-user_id',
            'event',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-event-task_id',
            'event',
            'task_id',
            'task',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-event-notification_id',
            'event',
            'notification_id',
            'notification',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }
}
