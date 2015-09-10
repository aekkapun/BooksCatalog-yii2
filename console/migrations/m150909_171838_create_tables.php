<?php

use yii\db\Schema;
use yii\db\Migration;

class m150909_171838_create_tables extends Migration
{
    public function up()
    {  
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'date_create' => $this->dateTime(),
            'date_update' => $this->dateTime(),
            'preview' => $this->string(),
            'date' => $this->dateTime(),
            'author_id' => $this->integer(),
        ]);        
        $this->createTable('{{%authors}}', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string()->notNull(),
            'lastname' => $this->string()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%books}}');
        $this->dropTable('{{%authors}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
