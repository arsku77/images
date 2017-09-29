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
    public $parent_id;
    public $post_id;
    public $author_id;
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
          //  [['post_id', 'author_id'], 'required'],
            [['text'], 'string', 'max' => Yii::$app->params['maxCommentLenghtInPost']],

        ];
    }

    /**
     * CommentForm constructor.
     * @param Post $post, User $user
     */
    public function __construct(Post $post, User $user)
    {
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

            $comment = new Comment();//sukuriam naują egzempliorių

//            $comment->parent_id = $this->parent_id;
            $comment->parent_id = 0;
            $comment->post_id = $this->post->getId();
            $comment->author_id = $this->user->getId();
            $comment->text = $this->text;

            if ($comment->save(false)) {
                return true;
            }
            return false;
        }
    }

}