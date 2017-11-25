<?php

namespace frontend\modules\post\widgets\postsList;

use frontend\modules\post\models\forms\PostFormForUpdate;
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
        $model = new PostFormForUpdate(null, $this->currentUserIdentity);

        return $this->render('block', [
            '$currentUserIdentity' => $this->currentUserIdentity,
            'model' => $model,
            'posts' => $this->posts,
        ]);
    }
}
