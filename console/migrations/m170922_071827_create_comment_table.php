<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m170922_071827_create_comment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->Integer()->notNull()->defaultValue(0),
            'post_id' => $this->Integer()->notNull(),
            'author_id' => $this->Integer()->notNull(),
            'text' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ],'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('comment');
    }
}
