<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use Yii;
?>

<?php if ($posts): ?>

    <?php foreach ($posts as $item): ?>


        <li class="comment">
            <div class="comment-user-image">
                <img src="<?php echo $item->getImage(); ?>" width="100px"/>
            </div>

            <div class="comment-info">
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

                    <div>
                        <p>cia bus  kazkokia forma</p>
                    </div>

                <hr>
            </div>
        </li>


    <?php endforeach; ?>

<?php endif; ?>