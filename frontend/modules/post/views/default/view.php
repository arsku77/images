<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use frontend\modules\post\widgets\commentsList\CommentsList;
use Yii;
$this->title = Yii::t('post','POST');
?>
    <div class="page-posts no-padding">

        <div class="row">
            <div class="page page-post col-sm-12 col-xs-12 post-82">


                <div class="blog-posts blog-posts-large">

                    <div class="row">


                        <!-- feed item -->
                        <article class="post col-sm-12 col-xs-12">
                            <div class="post-meta">
                                <div class="post-title">
                                    <?php if ($post->user): ?>
                                    <img src="<?php echo $post->user->getPicture(); ?>" class="author-image" />
                                    <div class="author-name">
                                        <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]); ?>">
                                            <?php echo Html::encode($post->user->username); ?>
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="post-type-image">
                                <img src="<?php echo $post->getImage(); ?>" alt="">
                            </div>
                            <div class="post-description">
                                <p><?php echo Html::encode($post->description); ?></p>
                            </div>
                            <div class="post-bottom">
                                <div class="post-likes">
                                    <a href="#" class="btn btn-secondary"><i class="fa fa-lg fa-heart-o"></i></a>
                                    <span class="likes-count"><?php echo $post->countLikes(); ?></span><span><?php echo Yii::t('post','Likes'); ?> &nbsp;</span>


                                    <a href="#" class="btn btn-secondary button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                                        Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                                    </a>
                                    <a href="#" class="btn btn-secondary button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                                        Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                                    </a>&nbsp;

                                </div>
                                <div class="post-comments">
                                    <a href="#"><?php echo $post->countCommentsToRedis(); echo Yii::t('post','Comments'); ?></a>
                                </div>
                                <div class="post-date">
                                    <span><?php echo Yii::$app->formatter->asDatetime($post->created_at); ?></span>
                                </div>

                                <div class="post-comments">
                                    <?php if ($post->isAuthor($currentUser)): ?>
                                        <?php echo Html::a('Delete this Post', ['/post/default/delete', 'id' => $post->getId()],
                                            [
                                                'class' => 'btn btn-danger',
                                                'style' => 'margin: 0px 0px 0px 0px;',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete Post?',
                                                    'method' => 'post',
                                                ],

                                            ]); ?>
                                    <?php endif; ?>
                                </div>



                            </div>
                        </article>
                        <!-- feed item -->

                        <!-- comment item -->
                        <div class="col-sm-12 col-xs-12">
                            <h4><?php echo $post->countCommentsToRedis(); echo Yii::t('post','Comments'); ?></h4>
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
                        <!-- comment item end -->

                        <!--post form-->
                        <div class="col-sm-12 col-xs-12">
                            <div class="comment-respond">
                                <h4><?php echo Yii::t('post','Leave a Reply'); ?></h4>
                                <?= $this->render('comment/_create', [
                                    'model' => $modelComment,
                                    'postId' => $post->id,
                                ]) ?>
                            </div>
                        </div>
                        <!--post form end-->
                    </div>
                </div>
            </div>

        </div>   <!-- row-->

    </div>


<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);