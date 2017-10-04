<?php
use yii\helpers\Html;
use frontend\models\User;
use yii\bootstrap\ActiveForm;
?>

<?php if ($list): ?>

    <?php foreach ($list as $item): ?>
        <div class="row">
            <p>Author:
                <b><?php echo Html::encode(User::getNickNameById($item['author_id'])); ?></b>
                <br>
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

                <?= $form->field($model, 'text')
                    ->textarea(['rows' => 4, 'value' => Html::encode($item['text'])])
                    ->label('Comment') ?>

                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'comment-update-button']) ?>
                    <?= Html::a('Delete comment', ['default/delete-comment', 'id' => $item['id']], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this comment?',
                            'method' => 'post',
                        ],
                    ]) ?>

                </div>

                <?php ActiveForm::end(); ?>
            </div>


            <?php else: ?>

                <?php echo Html::encode($item['text']); ?>

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
            </p>
        </div>

    <?php endforeach; ?>
<?php endif; ?>