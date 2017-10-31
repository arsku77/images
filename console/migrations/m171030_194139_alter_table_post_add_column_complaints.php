<?php

use yii\db\Migration;

class m171030_194139_alter_table_post_add_column_complaints extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post}}', 'complaints', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%post}}', 'complaints');
    }

}
