<?php

use yii\db\Migration;

/**
 * Handles the creation of table `chat`.
 */
class m201112_104159_create_chat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('chat', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('Создатель сообщения'),
            'message' => $this->text()->comment('Текст сообщения'),
            'blocked' => $this->boolean()->defaultValue(0)->comment('Блокирует ли сообщение'),
            'created_at' => $this->integer()->comment('Дата создания'),
            'updated_at' => $this->integer()->comment('Дата обновления'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('chat');
    }
}
