<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comments`.
 */
class m201021_152632_create_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Создатель комментария'),
            'email' => $this->string()->comment('Email автора комментатария'),
            'comment' => $this->text()->comment('Текст комментария'),
            'created_at' => $this->integer()->comment('Дата создания'),
            'updated_at' => $this->integer()->comment('Дата обновления'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('comments');
    }
}
