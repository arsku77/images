<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $filename
 * @property string $description
 * @property integer $created_at
 */
class Post extends \yii\db\ActiveRecord
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
        return 'post';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_at' => 'Created At',
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


    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * Get user of the post
     * @return User|null
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAuthor(User $user): bool
    {
        return $user->getId() == $this->user_id;

    }

    /**
     * Get author of the post
     * @return User|null
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Like current post by given user
     * @param \frontend\models\User $user
     */
    public function like(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->sadd("post:{$this->getId()}:likes", $user->getId());
        $redis->sadd("user:{$user->getId()}:likes", $this->getId());
    }

    /**
     * Unlike current post by given user
     * @param \frontend\models\User $user
     */
    public function unLike(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->srem("post:{$this->getId()}:likes", $user->getId());
        $redis->srem("user:{$user->getId()}:likes", $this->getId());
    }

    /**
     * @return mixed
     */
    public function countLikes()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$this->getId()}:likes");
    }

    /**
     * Check whether given user liked current post
     * @param \frontend\models\User $user
     * @return integer
     */
    public function isLikedBy(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->getId()}:likes", $user->getId());
    }

    /**
     * comment current post by given comment
     * @param \frontend\models\Comment $comment
     */
    public function commentAddToRedis(Comment $comment)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->sadd("post:{$this->getId()}:comments", $comment->getId());
    }

    /**
     * uncomment current post by given comment
     * @param \frontend\models\Comment $comment
     */
    public function unCommentRemToRedis(Comment $comment)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->srem("post:{$this->getId()}:comments", $comment->getId());
    }

    /**
     * @return mixed
     */
    public function countCommentsToRedis()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$this->getId()}:comments");
    }

    /**
     * Check whether given commented current post
     * @param \frontend\models\Comment $comment
     * @return integer
     */
    public function isCommentedBy(Comment $comment)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->getId()}:comments", $comment->getId());
    }

    /**
     * Add complaint to post from given user
     * @param \frontend\models\User $user
     * @return boolean
     */
    public function complain(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "post:{$this->getId()}:complaints";

        if (!$redis->sismember($key, $user->getId())) {
            $redis->sadd($key, $user->getId());
            $this->complaints++;
            return $this->save(false, ['complaints']);
        }
    }


}
