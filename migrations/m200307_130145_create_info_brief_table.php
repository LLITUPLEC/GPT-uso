<?php

use yii\db\Migration;

/**
 * Handles the creation of table `info_brief`.
 */
class m200307_130145_create_info_brief_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('info_brief', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer(11)->notNull(),
            'id_brief' => $this->integer(11)->notNull(),
            'status' => $this->tinyInteger(1)->null(),
        ]);

        $this->addForeignKey(
            'fk_info_brief_user',
            'info_brief', 'id_user',
            'users', 'id',
            'cascade'
        );

        $this->addForeignKey(
            'fk_info_brief_briefing',
            'info_brief', 'id_brief',
            'briefings', 'id',
            'cascade'
        );

        $this->createIndex('idx_user_id', 'info_brief', 'id_user');
        $this->createIndex('idx_brief_id', 'info_brief', 'id_brief');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_info_brief_user', 'info_brief');
        $this->dropForeignKey('fk_info_brief_briefing', 'info_brief');
        $this->dropIndex('idx_user_id', 'info_brief');
        $this->dropIndex('idx_brief_id', 'info_brief');
        $this->dropTable('info_brief');
    }
}
