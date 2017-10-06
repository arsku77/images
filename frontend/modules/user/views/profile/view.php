<?php
/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $currentUser frontend\models\User */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */


use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use dosamigos\fileupload\FileUpload;
?>

<h3><?php echo Html::encode($user->username); ?></h3>
<p><?php echo HtmlPurifier::process($user->about); ?></p>
<hr>
<img src="<?php echo $user->getPicture(); ?>" id="profile-picture" />
<br>
<div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
<div class="alert alert-danger display-none" id="profile-image-fail"></div>

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
        <?= Html::a('Delete', ['/user/profile/delete-picture', 'filename' => $user->picture], [
            'class' => 'btn btn-danger',
            'id' => 'btnDelete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this picture?',
                'method' => 'post',
            ],
        ]) ?>
    <?php endif; ?>
    <?= Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/user/profile/view', 'nickname' => $user->nickname], ['class' => 'btn btn-info']) ?>


<?php endif; ?>


<?php if(!($currentUser->equals($user))): ?>
    <?php if($currentUser->getFollowers($user)): ?>
        <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]); ?>" class="btn btn-info">Unsubscribe</a>
    <?php else: ?>
        <a href="<?php echo Url::to(['/user/profile/subscribe', 'id' => $user->getId()]); ?>" class="btn btn-info">Subscribe</a>
    <?php endif; ?>
<?php endif; ?>
<hr>

<?php if(!empty($currentUser->getMutualSubscriptionsTo($user))): ?>
    <h5>Friends, who are also following <?php echo Html::encode($user->username); ?>: </h5>
<?php endif; ?>

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

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal1">
    Subscriptions: <?php echo $user->countSubscriptions(); ?>
</button>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal2">
    Followers: <?php echo $user->countFollowers(); ?>
</button>


<!-- list posts -->
<?php if($postList): ?>
    <div class="row">
        This User:<h4> <?php echo Html::encode($user->username); ?> </h4>Posted yet!
        <?php foreach ($postList as $itemPost): ?>
            <div class="col-md-12">
                <a href="<?php echo Url::to(['/post/default/view', 'id' => $itemPost['id']]); ?>">
                    <h4>  <img src="<?php echo Yii::$app->storage->getFile($itemPost['filename']); ?>" height="30" />
                    <?php echo Html::encode($itemPost['description']); ?></h4>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="col-md-12">
        <h5>This User: <?php echo Html::encode($user->username); ?> Nobody posted yet! </h5>
    </div>
<?php endif; ?>
<!-- list posts end -->











<!-- Modal subscriptions -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Subscriptions</h4>
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
                <h4 class="modal-title" id="myModalLabel">Followers</h4>
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