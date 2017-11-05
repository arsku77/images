<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
    <?php $form = ActiveForm::begin([
        'id' => 'comment-form-in-post',
        'method' => 'post',
        'action' => ['comment/create', 'postId' => $postId]
    ]); ?>
    <p class="comment-form-comment">

        <?= $form->field($model, 'text')
            ->textarea([
                'id' => 'comment',
                'rows' => 6,
                'class' => 'form-control',
                'placeholder' => 'Text write here',
            ])->label('Comment respectfully comment. Thank you.') ?>
    </p>
    <p class="form-submit">
        <?= Html::submitButton('Comment', ['class' => 'btn btn-secondary', 'name' => 'comment-update-button']) ?>
    </p>

    <?php ActiveForm::end(); ?>
