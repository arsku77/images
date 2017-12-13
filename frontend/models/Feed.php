<?php

namespace frontend\models;

use frontend\models\traits\OwnersTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "feed".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $author_id
 * @property string $author_name
 * @property integer $author_nickname
 * @property string $author_picture
 * @property integer $post_id
 * @property string $post_filename
 * @property string $post_description
 * @property integer $post_created_at
 */
class Feed extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'author_id', 'post_id', 'post_created_at'], 'integer'],
            [['post_filename', 'post_created_at'], 'required'],
            [['post_description'], 'string'],
            [['author_nickname'], 'safe'],
            [['author_name', 'author_picture', 'post_filename'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'author_id' => 'Author ID',
            'author_name' => 'Author Name',
            'author_nickname' => 'Author Nickname',
            'author_picture' => 'Author Picture',
            'post_id' => 'Post ID',
            'post_filename' => 'Post Filename',
            'post_description' => 'Post Description',
            'post_created_at' => 'Post Created At',
        ];
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

use OwnersTrait;
    /**
     * @return mixed
     */
    public function countLikes()
    {
        /* @var $redis yii\redis\Connection */
        if (!empty(Yii::$app->redis)) {
            $redis = Yii::$app->redis;
            return $redis->scard("post:{$this->post_id}:likes");
        }
        return 0;
    }

    /**
     * @param $postId
     * @return mixed
     */
    public function countCommentsToRedis($postId)
    {
        /* @var $redis yii\redis\Connection */
        if (!empty(Yii::$app->redis)) {
            $redis = Yii::$app->redis;
            return $redis->scard("post:{$postId}:comments");
        }
        return 0;
    }

    /**
     * @param User $user
     * @return bool|mixed
     */
    public function isReported(User $user)
    {
        /* @var $redis yii\redis\Connection */
        if (!empty(Yii::$app->redis)) {
            $redis = Yii::$app->redis;
            return $redis->sismember("post:{$this->post_id}:complaints", $user->getId());
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isFollower(User $user): bool
    {
        return $user->getId() === $this->user_id;
    }


}