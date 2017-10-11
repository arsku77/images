<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use frontend\modules\post\widgets\commentsList\CommentsList;
use Yii;
?>
    <div class="page-posts no-padding">

        <div class="row">


            <!-- feed item -->
            <article class="post col-sm-12 col-xs-12">
                <div class="post-meta">
                    <div class="post-title">
                        <?php if ($post->user): ?>
                        <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]); ?>">
                            <img src="<?php echo $post->user->getPicture(); ?>" class="author-image" />
                            <div class="author-name"><a href="#"><?php echo Html::encode($post->user->username); ?></a></div>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="post-type-image">
                    <a href="#">
                        <img src="<?php echo $post->getImage(); ?>" alt="">
                    </a>
                </div>
                <div class="post-description">
                    <p><?php echo Html::encode($post->description); ?></p>
                </div>
                <div class="post-bottom">
                    <div class="post-likes">
                        <a href="#" class="btn btn-secondary"><i class="fa fa-lg fa-heart-o"></i></a>
                        <span><?php echo $post->countLikes(); ?> Likes</span>
                            Likes: <span class="likes-count"></span>

                            <a href="#" class="btn btn-primary button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                                Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                            </a>
                            <a href="#" class="btn btn-primary button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                                Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                            </a>&nbsp; <span>Comments: </span>

                    </div>
                    <div class="post-comments">
                        <a href="#"><?php echo $post->countCommentsToRedis(); ?> comments</a>

                    </div>
                    <div class="post-date">
                        <span><?php echo Yii::$app->formatter->asDatetime($post->created_at); ?></span>
                    </div>
                    <div class="post-report">
                        <a href="#">Report post</a>
                    </div>
                </div>
            </article>
            <!-- feed item -->

            <div class="col-sm-12 col-xs-12">
                <h4><?php echo $post->countCommentsToRedis(); ?> comments</h4>
                <div class="comments-post">

                    <div class="single-item-title"></div>
                    <div class="row">
                        <ul class="comment-list">
                            <?php echo CommentsList::widget([
                                'showLimit' => Yii::$app->params['maxCommentsInOnePost'],
                                'postId' => $post->id,
                            ]); ?>
                        </ul>
                    </div>

                </div>
            </div>

        <!--post form-->

            <div class="col-sm-12 col-xs-12">
                <div class="comment-respond">
                    <h4>Leave a Reply</h4>
                    <?php $this->render('comment/_create', [
                        'model' => $modelComment,
                        'postId' => $post->id,
                    ]) ?>
                </div>
            </div>
<!--post form end-->





        </div>   <!-- row-->




    </div>


<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);