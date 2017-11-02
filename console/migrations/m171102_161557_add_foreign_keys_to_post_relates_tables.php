<?php

use yii\db\Migration;

class m171102_161557_add_foreign_keys_to_post_relates_tables extends Migration
{
    public function safeUp()
    {
        //for table feed
        $this->addForeignKey('{{%fk-feed-post_id}}', '{{%feed}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
        //for table comment
        $this->addForeignKey('{{%fk-comment-post_id}}', '{{%comment}}', 'post_id', '{{%post}}', 'id', 'CASCADE');

    }

    public function safeDown()
    {
        //for table feed
        $this->dropForeignKey('{{%fk-feed-post_id}}','{{%feed}}');
        $this->dropIndex('{{%fk-feed-post_id}}','{{%feed}}');
        //for table comment
        $this->dropForeignKey('{{%fk-comment-post_id}}','{{%comment}}');
        $this->dropIndex('{{%fk-comment-post_id}}','{{%comment}}');
    }

}
