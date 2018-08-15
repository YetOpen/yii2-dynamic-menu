<?php
namespace esempla\dynamicmenu\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `sidebar_menu`.
 */
class m180524_093532_create_sidebar_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $defaultStatus = 7;

        $this->createTable('sidebar_menu', [
            'id' => $this->primaryKey(),
            'role' => $this->string(255)->notNull(),
            'menu_data' => $this->text()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue($defaultStatus),
            'row_version' => $this->integer(),
            'create_user' => $this->bigInteger()->notNull(),
            'create_datetime' => $this->dateTime()->notNull(),
            'update_user' => $this->bigInteger(),
            'update_datetime' => $this->dateTime()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('sidebar_menu');
    }
}
