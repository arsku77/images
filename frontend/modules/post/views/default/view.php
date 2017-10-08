<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use frontend\modules\post\widgets\commentsList\CommentsList;
?>
    <div class="post-default-index">

        <div class="row">

            <div class="col-md-12">
                <?php if ($post->user): ?>
                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]); ?>">
                        <img src="<?php echo $post->user->getPicture(); ?>" width="30"  />
                        <?php echo Html::encode($post->user->username); ?>
                    </a>
                <?php endif; ?>
            </div>

            <br>
            <br>
            <div class="col-md-12">
                <img src="<?php echo $post->getImage(); ?>" />
            </div>

            <div class="col-md-12">
                <?php echo Html::encode($post->description); ?>
            </div>
        </div>

        <div class="col-md-12">
            Likes: <span class="likes-count"><?php echo $post->countLikes(); ?></span>

            <a href="#" class="btn btn-primary button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
            </a>
            <a href="#" class="btn btn-primary button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
            </a>
            Comments: <span class="likes-count"><?php echo $post->countCommentsToRedis(); ?></span>
        </div>

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

                <div class="comment">
                    <h3 class="post-title">comments list</h3>
                    <?php echo CommentsList::widget([
                        'showLimit' => Yii::$app->params['maxCommentsInOnePost'],
                        'postId' => $post->id,
                    ]); ?>
                    <?= $this->render('comment/_create', [
                        'model' => $modelComment,
                        'postId' => $post->id,
                    ]) ?>
                </div>

            </div>
        </div>



    </div>


<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);