<?php

namespace frontend\modules\post\widgets\commentsList;

use Yii;
use yii\base\Widget;
use frontend\models\Comment;
use frontend\modules\post\models\forms\CommentForm;
use frontend\models\Post;

/**
 * @author admin
 */
class CommentsList extends Widget
{

    
    public $showLimit = null;
    public $postId = null;

    
    public function run()
    {
        $max = Yii::$app->params['maxCommentsInOnePost'];

        if ($this->showLimit) {
            $max = $this->showLimit;
        }

        $list = Comment::getCommentsList($max, $this->postId);
        $currentUser = Yii::$app->user->identity;
        $post = Post::findOne($this->postId);

        $model = new CommentForm(null, $post, $currentUser);

        return $this->render('block', [
            'list' => $list,
            'model' => $model,
            'post' => $post,
        ]);
    }
}
