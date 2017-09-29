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
            'action' => ['default/create-comment', 'id' => $post_id]
        ]); ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 6])->label('Comment') ?>

        <div class="form-group col-xs-12 floating-label-form-group controls">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'comment-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

</div>