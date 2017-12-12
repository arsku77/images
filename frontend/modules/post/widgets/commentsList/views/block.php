<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use Yii;
?>

<?php if ($list): ?>

    <?php foreach ($list as $item): ?>


        <li class="comment">
            <div class="comment-user-image">
                <img src="<?php echo $item->user->getPicture(); ?>" width="50px"/>
            </div>

            <div class="comment-info">
                <h4 class="author"><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $item->getAuthorNickName()]); ?>">
                        <?php echo Html::encode($item->getAuthorName()); ?>
                    </a>
                    <span>(<?php echo Yii::$app->formatter->asDatetime($item['created_at']); ?>)</span></h4>

                <?php if ($item->isAuthor(Yii::$app->user->identity)): ?>

                    <div class="col-lg-6">
                        <?php $form = ActiveForm::begin([
                            'id' => 'comment-update-form' . $item['id'],
                            'method' => 'post',
                            'action' => [
                                'comment/update',
                                'id' => $item['id'],
                                'postId' => $item['post_id']]
                        ]); ?>
                        <p class="comment-form-comment">
                            <?= $form->field($model, 'text')
                                ->textarea(['rows' => 4,
                                    'value' => Html::encode($item['text']),
                                    'class' => 'form-control comment-form-block-comment-text',
                                ])
                                ->label(Yii::t('post','Comment respectfully comment. Thank you.'), [
                                    'class' => 'comment-form-block-comment-text-label',
                                ]) ?>

                            <?= Html::submitButton('Update', [
                                'class' => 'btn btn-secondary comment-form-block-submit-button',
                                'name' => 'comment-update-button',
                            ]) ?>

                            <?= Html::a('Delete comment', ['comment/delete', 'id' => $item['id']], [
                                'class' => 'btn btn-danger comment-form-block-submit-button',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this comment?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </p>
                        <?php ActiveForm::end(); ?>
                    </div>


                <?php else: ?>

                    <p><?php echo Html::encode($item['text']); ?></p>

                <?php endif; ?>

                <?php if ($post->isAuthor(Yii::$app->user->identity)&&!$item->isAuthor(Yii::$app->user->identity)): ?>

                    <div>
                        <?= Html::a('Delete comment', ['comment/delete', 'id' => $item['id']], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this comment?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>

                <?php endif; ?>
                <hr>
            </div>
        </li>


    <?php endforeach; ?>

<?php endif; ?>