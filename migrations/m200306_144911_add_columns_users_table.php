<?php

use yii\db\Migration;

/**
 * Class m200306_144911_add_columns_users_table
 */
class m200306_144911_add_columns_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'users',
            'i_act',
            $this->integer(11)
                ->comment('info События')->defaultValue(0)
                ->after('email'));

        $this->addColumn(
            'users',
            'i_doc',
            $this->integer(11)
                ->comment('info Документы')->defaultValue(0)
                ->after('i_act'));

        $this->addColumn(
            'users',
            'i_instr',
            $this->integer(11)
                ->comment('info Инструктажи')->defaultValue(0)
                ->after('i_doc'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'i_act');
        $this->dropColumn('users', 'i_doc');
        $this->dropColumn('users', 'i_instr');
    }

}
