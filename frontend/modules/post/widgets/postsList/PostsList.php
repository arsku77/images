<?php

namespace frontend\modules\post\widgets\postsList;

use Yii;
use yii\base\Widget;
use frontend\models\Comment;
use frontend\modules\post\models\forms\CommentForm;
use frontend\models\Post;
use frontend\modules\post\models\forms\PostForm;

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
//        $currentUserIdentity = Yii::$app->user->identity;
//        $post = Post::findOne($this->postId);
//
        $model = new PostForm(null, $this->currentUserIdentity);

        return $this->render('block', [
            '$currentUserIdentity' => $this->currentUserIdentity,
            'model' => $model,
            'posts' => $this->posts,
        ]);
    }
}
