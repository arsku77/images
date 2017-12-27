<?php

namespace frontend\models;

use frontend\models\traits\OwnersTrait;
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
     * @return bool
     */
    public function beforeDelete()
    {
        $this->deleteCommentsToRedis();
        $this->deleteComplaintsToRedis();
        $this->deleteLikesToRedis();
        $this->deletePicture();
        return parent::beforeDelete();//really delete $this
    }

    /**
     * delete all comments one post in Redis
     *
     */
    public function deleteComplaintsToRedis():void
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $keyComplaints = "post:{$this->id}:complaints";
        $redis->del($keyComplaints);
    }

    /**
     * get all likes users ids one post
     * remove all post id from user likes in Redis
     * delete post record from redis
     *
     */
    public function deleteLikesToRedis():void
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        $keyLikes = "post:{$this->id}:likes";
        if ($idsUsers = $redis->smembers($keyLikes)) {
            foreach ($idsUsers as $idUser) {
                $redis->srem("user:{$idUser}:likes", $this->id);
            }
            $redis->del($keyLikes);
        }
    }

    /**
     * delete all comments one post in Redis
     *
     */
    public function deleteCommentsToRedis():void
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $keyComments = "post:{$this->id}:comments";
        $redis->del($keyComments);
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
     * @param $max integer
     * @return array|\yii\db\ActiveRecord[] or null
     */

    public static function getPostsList($max)
    {
        $order = ['updated_at' => SORT_DESC];
        return self::find()->with('user')->orderby($order)->limit($max)->all();

    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * Delete post picture from file system
     * @return boolean
     */
    public function deletePicture(): bool
    {
        if ($this->filename && Yii::$app->storage->deleteFile($this->filename)) {
            return true;
        }
        return false;
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
    use OwnersTrait;


    /**
     * Get author of the post
     * @return User|null
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
     * Get author name of relate table
     * @return string username|null
     */
    public function getAuthorName()
    {
        return $this->user->username;
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
