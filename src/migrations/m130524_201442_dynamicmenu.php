<?php

use yii\db\Migration;

class m130524_201442_dynamicmenu extends Migration
{
    public function up()
    {
        $tableOptions = null;

        $this->createTable('{{%menu}}', [
            'id' => $this->primaryKey(),
            'label'=>$this->string()->notNull(),
            'icon'=>$this->string()->notNull(),
            'class'=>$this->string()->notNull(),
            'url'=>$this->string()->notNull(),

        ], $tableOptions);


        $this->createTable('{{%menu_role}}', [
            'role' => $this->string()->notNull(),
            'menu_id'=>$this->integer(),

        ], $tableOptions);
        $this->addForeignKey(

            'fk-role-menu_id',
            'menu_role',
            'menu_id',
            'menu',
            'id'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-role-menu_id','menu_role');
        $this->dropTable('{{%menu}}');
        $this->dropTable('{{%menu_role}}');
    }
}
