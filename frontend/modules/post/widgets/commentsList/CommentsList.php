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
    public $post_id = null;

    
    public function run()
    {
        $max = Yii::$app->params['maxCommentsInOnePost'];

        if ($this->showLimit) {
            $max = $this->showLimit;
        }

        $list = Comment::getCommentsList($max, $this->post_id);
        $currentUser = Yii::$app->user->identity;
        $post = Post::findIdentity($this->post_id);

        $model = new CommentForm(null, $post, $currentUser);

        return $this->render('block', [
            'list' => $list,
            'model' => $model,
            'post' => $post,
        ]);
    }
}
