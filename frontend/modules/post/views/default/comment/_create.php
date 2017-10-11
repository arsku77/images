<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<p>
    Comment respectfully comment. Thank you.
</p>

<div class="row">
    <div class="col-lg-5">
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
                'placeholder' => 'Text',
                'aria-required' => 'true',
            ])->label('Comment') ?>
        </p>
        <p class="form-submit">
            <?= Html::submitButton('Send', ['class' => 'btn btn-secondary', 'name' => 'comment-button']) ?>
        </p>
        <?php ActiveForm::end(); ?>
    </div>
</div>

</div>