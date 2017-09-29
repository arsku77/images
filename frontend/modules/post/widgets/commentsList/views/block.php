<?php
use yii\helpers\Html;
use frontend\models\User;
use frontend\models\Post;
use yii\bootstrap\ActiveForm;
?>

<?php if ($list): ?>



    <?php foreach ($list as $item): ?>
        <p>Author:
        <?php echo Html::encode(User::getNickNameById($item['author_id'])); ?>
        <br>
        <?php if (User::findIdentity($item['author_id'])->equals(Yii::$app->user->identity)): ?>

            <div class="col-lg-5">
                <?php $form = ActiveForm::begin([
                    'id' => 'comment-update-form' . $item['id'],
                    'action' => [
                        'default/update-comment',
                        'id' => $item['id'], 'post_id' => $item['post_id']]
                ]); ?>

                <?= $form->field($model, 'text')
                    ->textarea(['rows' => 4, 'value' => Html::encode($item['text'])])
                    ->label('Comment') ?>

                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'comment-update-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>


        <?php else: ?>

            <?php echo Html::encode($item['text']); ?>


        <?php endif; ?>


        </p>


        <?php if (User::findIdentity(Post::findIdentity($item['post_id'])->user_id)->equals(Yii::$app->user->identity)): ?>

            <?= Html::a('Delete comment', ['default/delete-comment', 'id' => $item['id']], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this comment?',
                    'method' => 'post',
                ],
            ]) ?>

        <?php endif; ?>
        <hr>



    <?php endforeach; ?>
<?php endif; ?>