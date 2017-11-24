<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use Yii;
?>

<?php if ($posts): ?>

    <?php foreach ($posts as $item): ?>
        <hr>
        <li class="comment" style="padding: 0px 10px 0px 10px;height:160px;">
            <div class="comment-user-image" style="padding:0px 10px 0px 10px;margin-top: 10px">
                <a href="<?php echo Url::to(['/post/default/view', 'id' => $item->id]); ?>">
                    <img src="<?php echo $item->getImage(); ?>" style="width:260px;max-height:156px;"/>
                </a>
            </div>

            <div class="comment-info" style="padding:0px 10px 0px 10px;margin-left:285px;margin-top: 10px">
                <h5 class="author" style="margin: 0px 0px 0px 0px">
                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $item->getAuthorNickName()]); ?>">
                        <?php echo Html::encode($item->getAuthorName()); ?>
                    </a>
                    <span>(<?php echo Yii::$app->formatter->asDatetime($item['updated_at']); ?>)</span>
                    <a href="<?php echo Url::to(['/post/default/view', 'id' => $item->id]); ?>">
                        <?php echo $item->countCommentsToRedis(); echo Yii::t('post', 'Comments'); ?>
                        <?php echo $item->countLikes(); echo Yii::t('post', 'Likes'); ?>
                    </a>
                </h5>

                <?php if ($item->isAuthor(Yii::$app->user->identity)): ?>

                    <!--                    <p class="comment-form-comment">-->
                    <?php $form = ActiveForm::begin([
                        'id' => 'post-update-form' . $item['id'],
                        'method' => 'post',
                        'action' => [
                            '/post/default/update',
                            'id' => $item['id'],
                        ]
                    ]); ?>

                    <?= $form->field($model, 'description')
                        ->textarea(['rows' => 3,
                            'value' => Html::encode($item['description']),
                            'class' => 'form-control',
                            'style' => 'font-weight:200;padding:0px 0px 0px 0px;margin: 0px 0px 0px 0px;width:60%',
                        ])
                        ->label(Yii::t('post','News respectfully post. Thank you.'), [
                            'style' => 'font-weight:50;padding:0px 0px 0px 0px;margin: 10px 0px 0px 0px;width:60%',
                        ]) ?>

                    <?= Html::submitButton('Update', [
                        'class' => 'btn btn-secondary',
                        'name' => 'comment-update-button',
                        'style' => 'margin: -30px 0px 0px 0px;',
                    ]) ?>

                    <?= Html::a('Delete comment', ['comment/delete', 'id' => $item['id']], [
                        'class' => 'btn btn-danger',
                        'style' => 'margin: -30px 0px 0px 0px;',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this comment?',
                            'method' => 'post',
                        ],
                    ]) ?>

                    <?php ActiveForm::end(); ?>
                    <!--                    </p>-->

                <?php else: ?>

                    <p style="margin: 10px 0px 0px 0px"><?php echo Html::encode($item['description']); ?></p>

                <?php endif; ?>

            </div>
        </li>


    <?php endforeach; ?>

<?php endif; ?>