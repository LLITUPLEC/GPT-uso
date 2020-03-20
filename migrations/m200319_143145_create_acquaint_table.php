<?php

use yii\db\Migration;

/**
 * Handles the creation of table `acquaint`.
 */
class m200319_143145_create_acquaint_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('acquaint', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('Кто ознакомился'),
            'file_id' => $this->integer()->comment('С чем ознакомился'),
            'created_at' => $this->integer()->comment('Дата ознакомления'),
            'updated_at' => $this->integer(),
        ]);

        // создание реляционной связи на пользователей
        $this->addForeignKey(
            'fk_acquaint_user',
            'acquaint', 'user_id',
            'users', 'id',
            'cascade'
        );
        // создание реляционной связи на тесты
        $this->addForeignKey(
            'fk_acquaint_file',
            'acquaint', 'file_id',
            'files', 'id',
            'cascade'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_acquaint_user', 'acquaint');
        $this->dropForeignKey('fk_acquaint_file', 'acquaint');
        $this->dropTable('acquaint');
    }
}
