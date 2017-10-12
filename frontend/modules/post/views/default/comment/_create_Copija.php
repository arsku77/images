<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<?php
echo '<pre>';
print_r($model);
echo '<pre>';die;
?>

        <?php $form = ActiveForm::begin([
            'id' => 'comment-form',
            'method' => 'post',
            'action' => ['default/create-comment', 'postId' => $postId]
        ]); ?>
        <p class="comment-form-comment">
            <?= $form->field($model, 'text')->textarea([
                'rows' => 6,
                'name' => 'comment',
                'class' => 'form-control',
                'placeholder' => 'Text write here',
              ])->label('Comment respectfully comment. Thank you.') ?>
        </p>

<?= Html::submitButton('Update', ['class' => 'btn btn-secondary', 'name' => 'comment-update-button']) ?>

        <?php ActiveForm::end(); ?>

