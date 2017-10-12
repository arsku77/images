<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\User;
use yii\bootstrap\ActiveForm;
use Yii;
?>

<?php if ($list): ?>

    <?php foreach ($list as $item): ?>


        <li class="comment">
            <div class="comment-user-image">
                <img src="<?php User::findIdentity($item['author_id'])->picture; ?>">
            </div>

            <div class="comment-info">
                <h4 class="author"><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => User::getNickNameById($item['author_id'])]); ?>">
                        <?php echo Html::encode(User::getUserNameById($item['author_id'])); ?>
                    </a>
                    <span>(<?php echo Yii::$app->formatter->asDatetime($item['created_at']); ?>)</span></h4>

                <?php if ($item->isAuthor(Yii::$app->user->identity)): ?>

            <div class="col-lg-6">
                <?php $form = ActiveForm::begin([
                    'id' => 'comment-update-form' . $item['id'],
                    'method' => 'post',
                    'action' => [
                        'default/update-comment',
                        'id' => $item['id'],
                        'postId' => $item['post_id']]
                ]); ?>
                <p class="comment-form-comment">
                <?= $form->field($model, 'text')
                    ->textarea(['rows' => 4,
                        'value' => Html::encode($item['text']),
                        'class' => 'form-control',
                        ])
                    ->label('Comment respectfully comment. Thank you.') ?>
                </p>
                <p class="form-submit">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-secondary', 'name' => 'comment-update-button']) ?>
                    <?= Html::a('Delete comment', ['default/delete-comment', 'id' => $item['id']], [
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

                    <p><?php echo Html::encode($item['text']); ?></p>

            <?php endif; ?>

            <?php if ($post->isAuthor(Yii::$app->user->identity)&&!$item->isAuthor(Yii::$app->user->identity)): ?>

                    <div>
                    <?= Html::a('Delete comment', ['default/delete-comment', 'id' => $item['id']], [
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