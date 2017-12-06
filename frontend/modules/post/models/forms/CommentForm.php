<?php
/**
 * Created by PhpStorm.
 * User: arsku
 * Date: 2017.09.28
 * Time: 11:19
 */

namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Comment;
use frontend\models\Post;
use frontend\models\User;

class CommentForm extends Model
{
    public $id;
//    public $parent_id;
//    public $post_id;
//    public $author_id;
    public $text;
    private $post;
    private $user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['parent_id', 'post_id', 'author_id'], 'integer'],
            //  [['parent_id'], 'integer'],
            [['text'], 'required'],
            [['text'], 'string', 'max' => Yii::$app->params['maxCommentLengthInPost']],

        ];
    }

    /**
     * CommentForm constructor.
     * @param integer $id = null,
     * @param Post $post,
     * @param User $user
     */
    public function __construct($id = null, Post $post, User $user)
    {
        $this->id = $id;
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Comment save method
     * @return boolean
     */
    public function save()
    {

        if ($this->validate()) {

            $comment = $this->findComment($this->id);

            $comment->parent_id = 0;
            $comment->post_id = $this->post->getId();
            $comment->author_id = $this->user->getId();
            $comment->text = $this->text;

            if ($comment->save(false)) {
                $this->post->commentAddToRedis($comment);
                return true;
            }
            return false;
        }
    }

    /**
     * @param null $id
     * @return Comment models empty|Comment models by id static
     */
    private function findComment($id = null)
    {
        if ($id) {
            return $comment = Comment::findOne($id);//for update
        }
        return $comment = new Comment();//for add new comment
    }

}