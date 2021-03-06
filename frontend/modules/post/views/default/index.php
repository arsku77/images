<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use frontend\modules\post\widgets\postsList\PostsList;
use Yii;
$this->title = Yii::t('post','New POSTS');
?>
    <div class="page-posts no-padding">

        <div class="row">
            <div class="page page-post col-sm-12 col-xs-12 post-82">


                <div class="blog-posts blog-posts-large">

                    <div class="row">


                        <!-- comment item -->
                        <hr>
                        <div class="col-sm-12 col-xs-12">
                            <h4><?php echo Yii::t('post','New POSTS'); ?></h4>
                            <div class="comments-post">
                                <div class="single-item-title"></div>
                                <div class="row">
                                    <ul class="comment-list" style="padding: 0px 10px 0px 10px">
                                        <?php echo PostsList::widget([
                                            'showLimit' => Yii::$app->params['limitPostsInPostList'],
                                            'posts' => $posts,
                                            'currentUserIdentity' => $currentUserIdentity,
                                        ]); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- comment item end -->

                    </div>
                </div>
            </div>

        </div>   <!-- row-->

    </div>


<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);