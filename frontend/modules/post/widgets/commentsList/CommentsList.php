<?php

namespace frontend\modules\post\widgets\commentsList;

use Yii;
use yii\base\Widget;
use frontend\models\Comment;

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

        return $this->render('block', [
            'list' => $list,
        ]);
    }
}
