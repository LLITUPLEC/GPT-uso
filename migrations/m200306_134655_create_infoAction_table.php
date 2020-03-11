<?php

use yii\db\Migration;

/**
 * Handles the creation of table `infoAction`.
 */
class m200306_134655_create_infoAction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('info_action', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer(11)->notNull(),
            'id_action' => $this->integer(11)->notNull(),
            'status' => $this->tinyInteger(1)->null(),
        ]);

        $this->addForeignKey(
            'fk_user_id',
            'info_action', 'id_user',
            'users', 'id',
            'cascade'
        );

        $this->addForeignKey(
            'fk_action_id',
            'info_action', 'id_action',
            'activities', 'id',
            'cascade'
        );

        $this->createIndex('idx_user_id', 'info_action', 'id_user');
        $this->createIndex('idx_action_id', 'info_action', 'id_action');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_id', 'info_action');
        $this->dropForeignKey('fk_action_id', 'info_action');
        $this->dropIndex('idx_user_id', 'info_action');
        $this->dropIndex('idx_action_id', 'info_action');
        $this->dropTable('info_action');
    }

}
