<?php
use yii\helpers\Html;
use frontend\models\User;
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
        <hr>



    <?php endforeach; ?>
<?php endif; ?>