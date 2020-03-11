<?php

use yii\db\Migration;

/**
 * Handles the creation of table `info_doc`.
 */
class m200307_123822_create_info_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('info_doc', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer(11)->notNull(),
            'id_doc' => $this->integer(11)->notNull(),
            'status' => $this->tinyInteger(1)->null(),
        ]);

        $this->addForeignKey(
            'fk_info_doc_user',
            'info_doc', 'id_user',
            'users', 'id',
            'cascade'
        );

        $this->addForeignKey(
            'fk_info_doc_file',
            'info_doc', 'id_doc',
            'files', 'id',
            'cascade'
        );

        $this->createIndex('idx_user_id', 'info_doc', 'id_user');
        $this->createIndex('idx_doc_id', 'info_doc', 'id_doc');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_info_doc_user', 'info_doc');
        $this->dropForeignKey('fk_info_doc_file', 'info_doc');
        $this->dropIndex('idx_user_id', 'info_doc');
        $this->dropIndex('idx_doc_id', 'info_doc');
        $this->dropTable('info_doc');
    }
}
