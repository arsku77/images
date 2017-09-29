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
            [['parent_id', 'post_id', 'author_id'], 'integer'],
            [['post_id', 'author_id'], 'required'],
            [['text'], 'string', 'max' => Yii::$app->params['maxCommentLenghtInPost']],
            // verifyCode needs to be entered correctly
           // ['verifyCode', 'captcha'],

        ];
    }

    /**
     * CommentForm constructor.
     * @param Comment $comment
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
//            echo '<pre>';
//            print_r($this);
//            echo '<pre>';die;


            $comment = new Comment();//sukuriam naują egzempliorių

            $comment->parent_id = $this->parent_id;//jo savybei suteikiam duomenį iš formos
            $comment->post_id = $this->post->getId();//jo savybei suteikiam duomenį iš formos
            $comment->author_id = $this->user->getId();//jo savybei suteikiam duomenį iš formos
            $comment->text = $this->text;//jo savybei suteikiam duomenį iš formos

            if ($comment->save(false)) {
                return true;
            }
            return false;
        }
    }


}