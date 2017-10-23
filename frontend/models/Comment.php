<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $post_id
 * @property integer $author_id
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 */
class Comment extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'post_id' => 'Post ID',
            'author_id' => 'Author ID',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAuthor(User $user): bool
    {
        return $user->getId() == $this->author_id;

    }

    /**
     * Get author of the comments
     * @return User|null
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }


    /**
     * Get author name of relate table
     * @return string username|null
     */
    public function getAuthorName()
    {
        return $this->user->username;
    }

    /**
     * Get author name of relate table
     * @return string nickname|id
     */
    public function getAuthorNickName()
    {
        return ($this->user->nickname) ? $this->user->nickname : $this->user->id;
    }

    /**
     * Get user of the post
     * @return User|null
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }


    /**
     * Get post about the comments
     * @return Post|null
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @param $max integer
     * @param $post_id integer
     * @return array|\yii\db\ActiveRecord[] or null
     */

    public static function getCommentsList($max, $post_id)
    {
        $order = ['created_at' => SORT_DESC];
        return self::find()->with('user')->where(['post_id' => $post_id])->orderby($order)->limit($max)->all();

    }

}
