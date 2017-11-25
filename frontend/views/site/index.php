<?php
/* @var $this yii\web\View */
/* @var $currentUser frontend\models\User */
/* @var $feedItems frontend\models\Feed */

use yii\web\JqueryAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\bootstrap\ActiveForm;


$this->title = Yii::t('index','Subscribed');
?>

    <div class="page-posts no-padding">
        <div class="row">
            <div class="page page-post col-sm-12 col-xs-12">
                <h4><?php echo Yii::t('index','Subscribed news'); ?></h4>

                <div class="blog-posts blog-posts-large">

                    <div class="row">



                        <?php if ($feedItems): ?>
                        <?php foreach ($feedItems as $feedItem): ?>
                            <?php /* @var $feedItem Feed */ ?>

                            <article class="post col-sm-12 col-xs-12">
                                <div class="post-meta">
                                    <div class="post-title">
                                        <img src="<?php echo $feedItem->author_picture; ?>" class="author-image" />
                                        <div class="author-name">
                                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($feedItem->author_nickname) ? $feedItem->author_nickname : $feedItem->author_id]); ?>">
                                                <?php echo Html::encode($feedItem->author_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-type-image">
                                    <a href="<?php echo Url::to(['/post/default/view', 'id' => $feedItem->post_id]); ?>">
                                        <img src="<?php echo Yii::$app->storage->getFile($feedItem->post_filename); ?>" alt="" />
                                    </a>
                                </div>
                                <div class="post-description">
                                    <p><?php echo HtmlPurifier::process($feedItem->post_description); ?></p>
                                </div>
                                <div class="post-bottom">

                                    <div class="post-likes">
                                        <i class="fa fa-lg fa-heart-o"></i>
                                        <span class="likes-count"><?php echo $feedItem->countLikes(); ?></span>
                                        <a href="#" class="btn btn-secondary button-unlike <?php echo ($currentUser->likesPost($feedItem->post_id)) ? "" : "display-none"; ?>" data-id="<?php echo $feedItem->post_id; ?>">
                                            Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                                        </a>
                                        <a href="#" class="btn btn-secondary button-like <?php echo ($currentUser->likesPost($feedItem->post_id)) ? "display-none" : ""; ?>" data-id="<?php echo $feedItem->post_id; ?>">
                                            Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                                        </a>
                                    </div>

                                    <div class="post-comments">
                                        <a href="<?php echo Url::to(['/post/default/view', 'id' => $feedItem->post_id]); ?>">
                                            <?php echo $feedItem->countCommentsToRedis($feedItem->post_id); ?>
                                            &nbsp; <?php echo Yii::t('post', 'Сomments'); ?>
                                        </a>
                                    </div>

                                    <div class="post-date">
                                        <span><?php echo Yii::$app->formatter->asDatetime($feedItem->post_created_at); ?></span>
                                    </div>
                                    <div class="post-report">
                                        <?php if (!$feedItem->isReported($currentUser)): ?>
                                            <a href="#" class="btn btn-default button-complain" data-id="<?php echo $feedItem->post_id; ?>">
                                                <?php echo Yii::t('post', 'Report post'); ?> <i class="fa fa-cog fa-spin fa-fw icon-preloader" style="display:none"></i>
                                            </a>
                                        <?php else: ?>
                                            <?php echo Yii::t('post', 'Post has been reported'); ?>
                                        <?php endif; ?>
                                        <!--delete button if user is follover-->
                                        <?php if ($feedItem->isFollower($currentUser)): ?>
                                            <?php echo Html::a('Delete this item, no Post', ['/post/default/delete-feed', 'id' => $feedItem->getId()],
                                                [
                                                    'class' => 'btn btn-danger',
                                                    'style' => 'margin: 0px 0px 0px 0px;',
                                                    'title' => Yii::t('post','Delete this feeds only, but not news or picture'),
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                        'method' => 'post',
                                                    ],

                                                ]); ?>
                                        <?php endif; ?>
                                        <!--delete button if user is follover-->


                                    </div>

                                </div>
                            </article>

                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <div class="col-md-12">
                            <?php echo Yii::t('index','Nobody posted yet!'); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
    </div>


<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);
$this->registerJsFile('@web/js/complaints.js', [
    'depends' => JqueryAsset::className(),
]);