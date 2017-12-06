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
    <?= $form->field($model, 'username')
        ->textInput([
            'value' => Html::encode($model->username),
            'class' => 'form-control',
        ])
        ->label(Yii::t('user','You can change your name here'), [
            'style' => 'font-weight:50;padding:0px 0px 0px 0px;margin: 10px 0px 0px 0px;width:60%',
        ]) ?>

    <?= $form->field($model, 'about')
        ->textarea(['rows' => 4,
            'value' => Html::encode($model->about),
            'class' => 'form-control',
        ])
        ->label(Yii::t('user','Write about yourself'), [
            'style' => 'font-weight:50;padding:0px 0px 0px 0px;margin: 10px 0px 0px 0px;width:60%',
        ]) ?>

    <?= Html::submitButton('Update', [
        'class' => 'btn btn-secondary',
        'style' => 'margin: 0px 0px 0px 0px;',
        'name' => 'profile-update-button',
    ]) ?>

<?php ActiveForm::end(); ?>

