<?php

namespace frontend\modules\post\widgets\postsList;

use Yii;
use yii\base\Widget;
use frontend\models\Comment;
use frontend\modules\post\models\forms\CommentForm;
use frontend\models\Post;

/**
 * @author admin
 */
class PostsList extends Widget
{

    
    public $showLimit = null;
    public $posts;
    public $currentUserIdentity;

    
    public function run()
    {
//        $max = Yii::$app->params['limitPostsInPostList'];
//
//        if ($this->showLimit) {
//            $max = $this->showLimit;
//        }

//        $list = Comment::getCommentsList($max, $this->postId);
//        $currentUser = Yii::$app->user->identity;
//        $post = Post::findOne($this->postId);
//
//        $model = new CommentForm(null, $post, $currentUser);

        return $this->render('block', [
            '$currentUserIdentity' => $this->currentUserIdentity,
            'posts' => $this->posts,
        ]);
    }
}
