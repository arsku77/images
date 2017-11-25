<?php
use Yii;
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
                'rows' => 4,
                'class' => 'form-control',
                'style' => 'font-weight:200;padding:0px 0px 0px 0px;margin: 0px 0px 0px 0px;width:80%',
                'placeholder' => 'Text write here',
            ])->label(Yii::t('post','Comment respectfully comment. Thank you.')) ?>
    </p>
    <p class="form-submit">
        <?= Html::submitButton('Comment', ['class' => 'btn btn-secondary', 'name' => 'comment-update-button']) ?>
    </p>

    <?php ActiveForm::end(); ?>
