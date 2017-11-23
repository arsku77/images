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
                <a href="<?php echo Url::to(['/post/default/view', 'id' => $item->id]); ?>">
                    <img src="<?php echo $item->getImage(); ?>" style="width:200px;max-height:120px;"/>
                </a>
            </div>

            <div class="comment-info" style="padding:10px 10px 10px 10px;margin-left:220px;">
                <h4 class="author"><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $item->getAuthorNickName()]); ?>">
                        <?php echo Html::encode($item->getAuthorName()); ?>
                    </a>
                    <span>(<?php echo Yii::$app->formatter->asDatetime($item['created_at']); ?>)</span></h4>
                <div class="post-comments">
                    <a href="<?php echo Url::to(['/post/default/view', 'id' => $item->id]); ?>">
                        <?php echo $item->countCommentsToRedis(); echo Yii::t('post', 'Comments'); ?>
                        <?php echo $item->countLikes(); echo Yii::t('post', 'Likes'); ?>
                    </a>
                </div>

                <?php if ($item->isAuthor(Yii::$app->user->identity)): ?>

                    <div class="col-lg-6">
                        <?php $form = ActiveForm::begin([
                            'id' => 'post-update-form' . $item['id'],
                            'method' => 'post',
                            'action' => [
                                'post/update',
                                'id' => $item['id'],
                                ]
                        ]); ?>
                        <p class="comment-form-comment">
                            <?= $form->field($model, 'description')
                                ->textarea(['rows' => 3,
                                    'value' => Html::encode($item['description']),
                                    'class' => 'form-control',
                                ])
                                ->label(Yii::t('post','News respectfully post. Thank you.')) ?>

                            <?= Html::submitButton('Update', ['class' => 'btn btn-secondary', 'name' => 'comment-update-button']) ?>

                            <?= Html::a('Delete comment', ['comment/delete', 'id' => $item['id']], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this comment?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </p>
                        <?php ActiveForm::end(); ?>
                    </div>


                <?php else: ?>

                    <p><?php echo Html::encode($item['description']); ?></p>

                <?php endif; ?>

            </div>
        </li>


    <?php endforeach; ?>

<?php endif; ?>