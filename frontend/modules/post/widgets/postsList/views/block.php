<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use Yii;
?>

<?php if ($posts): ?>

    <?php foreach ($posts as $item): ?>

        <li class="comment" style="padding:10px 10px 10px 10px;height:140px;">
            <div class="comment-user-image" style="padding-right: 10px">
                <img src="<?php echo $item->getImage(); ?>" style="width:200px;max-height:120px;"/>
            </div>

            <div class="comment-info" style="padding:10px 10px 10px 10px;margin-left:220px;">
                <h4 class="author"><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $item->getAuthorNickName()]); ?>">
                        <?php echo Html::encode($item->getAuthorName()); ?>
                    </a>
                    <span>(<?php echo Yii::$app->formatter->asDatetime($item['created_at']); ?>)</span></h4>

                <?php if ($item->isAuthor(Yii::$app->user->identity)): ?>

                    <div class="col-lg-6">
                        <p>cia bus detali forma</p>
                    </div>


                <?php else: ?>

                    <p><?php echo Html::encode($item['description']); ?></p>

                <?php endif; ?>

            </div>
        </li>


    <?php endforeach; ?>

<?php endif; ?>