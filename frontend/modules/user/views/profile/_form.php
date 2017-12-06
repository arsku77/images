<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
    'id' => 'profile-update-form' . $model->id,
    'method' => 'post',
    'action' => [
        'profile/update',
        'id' => $model->id,
        ]
]); ?>
    <?= $form->field($model, 'about')
        ->textarea(['rows' => 4,
            'value' => Html::encode($model->about),
            'class' => 'form-control',
        ])
        ->label(Yii::t('post','Comment respectfully comment. Thank you.'), [
            'style' => 'font-weight:50;padding:0px 0px 0px 0px;margin: 10px 0px 0px 0px;width:60%',
        ]) ?>

    <?= Html::submitButton('Update', [
        'class' => 'btn btn-secondary',
        'style' => 'margin: 0px 0px 0px 0px;',
        'name' => 'comment-update-button',
    ]) ?>

    <?= Html::a('Delete comment', ['comment/delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'style' => 'margin: 0px 0px 0px 0px;',
        'data' => [
            'confirm' => 'Are you sure you want to delete this comment?',
            'method' => 'post',
        ],
    ]) ?>
<?php ActiveForm::end(); ?>

