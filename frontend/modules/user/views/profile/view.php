<?php
/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $currentUser frontend\models\User */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use dosamigos\fileupload\FileUpload;
$this->title = Html::encode($user->username);
?>

<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">


            <div class="blog-posts blog-posts-large">

                <div class="row">

                    <!-- profile -->
                    <article class="profile col-sm-12 col-xs-12">

                        <div class="profile-title">
                            <img src="<?php echo $user->getPicture(); ?>" id="profile-picture" class="author-image" />
                            <div class="author-name"><?php echo Html::encode($user->username); ?></div>

                            <?php if ($currentUser->equals($user)): ?>
                                <?= FileUpload::widget([
                                    'model' => $modelPicture,
                                    'attribute' => 'picture',
                                    'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
                                    'options' => ['accept' => 'image/*'],
                                    'clientEvents' => [
                                        'fileuploaddone' => 'function(e, data) {
                if (data.result.success) {
                    $("#profile-image-success").show();
                    $("#profile-image-fail").hide();
                    $("#profile-picture").attr("src", data.result.pictureUri);
                } else {
                    $("#profile-image-fail").html(data.result.errors.picture).show();
                    $("#profile-image-success").hide();
                }               
            }',
                                    ],
                                ]); ?>

                                <?php if ($user->picture > ''): ?>
                                    <?= Html::a('Delete picture', ['/user/profile/delete-picture', 'filename' => $user->picture], [
                                        'class' => 'btn btn-danger',
                                        'id' => 'btnDelete',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this picture?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                <?php endif; ?>
                                <?= Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/user/profile/view', 'nickname' => ($user->nickname ? $user->nickname : $user->id)], ['class' => 'btn btn-info']) ?>

                            <?php endif; ?>


                            <!--                            <a href="#" class="btn btn-default">Upload profile image</a>-->
                            <a href="#" class="btn btn-default">Edit profile</a>
                            <?php echo Html::a('Edit profile', ['profile/update', 'id' => $user->getId()], [
                                'class' => 'btn btn-secondary',
                                'id' => 'btnShowUpdate',
                                'data' => [
                                    'method' => 'post',
                                ],
                            ]) ?>

                            <br/>
                            <br/>

                            <div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
                            <div class="alert alert-danger display-none" id="profile-image-fail"></div>
                            <?php if ($currentUser && !$currentUser->equals($user)): ?>
                                <?php if($currentUser->subscribedUser($user->getId())): ?>
                                    <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]); ?>" class="btn btn-info"><?php echo Yii::t('profile', 'Unsubscribe'); ?></a>
                                <?php else: ?>
                                    <a href="<?php echo Url::to(['/user/profile/subscribe', 'id' => $user->getId()]); ?>" class="btn btn-info"><?php echo Yii::t('profile', 'Subscribe'); ?></a>
                                <?php endif; ?>
                                <hr>
                                <h5><?php echo Yii::t('profile','Friends, who are also following');  echo Html::encode($user->username); ?>: </h5>
                                <div class="row">
                                    <?php foreach ($currentUser->getMutualSubscriptionsTo($user) as $item): ?>
                                        <div class="col-md-12">
                                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($item['nickname']) ? $item['nickname'] : $item['id']]); ?>">
                                                <?php echo Html::encode($item['username']); ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <hr>
                            <?php endif; ?>

                        </div>
                        <?php if ($user->about): ?>
                            <div class="profile-description">
                                <h5><p><?php echo HtmlPurifier::process($user->about); ?></p></h5>
                                <hr>
                            </div>
                        <?php endif;?>
                        <!-- profile update form-->
                        <?php if ($modelProfile->flagShowUpdateForm): ?>
                            <div class="comment-respond">
                                <h4><?php echo Yii::t('user','Update profile here'); ?></h4>
                                <?= $this->render('_form', [
                                    'model' => $modelProfile,
                                ]) ?>
                            </div>
                        <?php endif;?>

                        <!--profile update form end-->





                        <div class="profile-bottom">
                            <div class="profile-post-count">
                                <span><?php echo $user->getPostCount(); echo Yii::t('post', 'Posts'); ?></span>
                            </div>
                            <div class="profile-followers">
                                <a href="#" data-toggle="modal" data-target="#myModal2"><?php echo $user->countFollowers(); echo Yii::t('profile', 'followers'); ?></a>
                            </div>
                            <div class="profile-following">
                                <a href="#" data-toggle="modal" data-target="#myModal1"><?php echo $user->countSubscriptions(); echo Yii::t('profile', 'following'); ?></a>
                            </div>
                        </div>


                    </article>
                    <!-- profile end -->

                    <div class="col-sm-12 col-xs-12">
                        <div class="row profile-posts">
                            <!-- list posts -->
                            <?php if($postList): ?>

                                <p><?php echo Yii::t('profile', 'This User:'); echo Html::encode($user->username);  echo Yii::t('profile', 'Posted yet!'); ?></p>
                                <?php foreach ($postList as $itemPost): ?>
                                    <div class="col-md-4 profile-post">
                                        <a href="<?php echo Url::to(['/post/default/view', 'id' => $itemPost['id']]); ?>">
                                            <img src="<?php echo Yii::$app->storage->getFile($itemPost['filename']); ?>" class="author-image" />
                                        </a>
                                    </div>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <div class="col-md-4 profile-post">
                                    <?php echo Yii::t('profile', 'This User:'); echo Html::encode($user->username);  echo Yii::t('profile', 'Nobody posted yet!'); ?>
                                </div>
                            <?php endif; ?>
                            <!-- list posts end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>





<!-- Modal subscriptions -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('profile', 'Following'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getSubscriptions() as $subscription): ?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($subscription['nickname']) ? $subscription['nickname'] : $subscription['id']]); ?>">
                                <?php echo Html::encode($subscription['username']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal subscriptions -->

<!-- Modal followers -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('profile', 'Followers'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getFollowers() as $follower): ?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($follower['nickname']) ? $follower['nickname'] : $follower['id']]); ?>">
                                <?php echo Html::encode($follower['username']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal followers -->