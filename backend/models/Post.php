<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $filename
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $complaints
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    public function beforeDelete()
    {
//        echo '<pre>';
//        print_r($this);
//        echo '<pre>';die;

        $this->deleteCommentsToRedis();
        $this->deleteComplaintsToRedis();
        $this->deleteLikesToRedis();
        return parent::beforeDelete();//really delete $this
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
            'updated_at' => 'Updated At',
            'complaints' => 'Complaints',
        ];
    }

    /**
     * @return $this
     */
    public static function findComplaints()
    {
        return Post::find()->where('complaints > 0')->orderBy('complaints DESC');
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * Approve post (delete complaints) if it looks ok
     * @return boolean
     */

    /**
     * Get user of the post
     * @return User|null
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public function approve()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "post:{$this->id}:complaints";
        $redis->del($key);

        $this->complaints = 0;
        return $this->save(false, ['complaints']);
    }


    /**
     * delete all comments ene post in Redis
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
     * delete all comments ene post in Redis
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


}
