<?php
/* @var $this yii\web\View */
/* @var $model frontend\modules\post\models\forms\PostForm */

use Yii;
use yii\widgets\ActiveForm;
use yii\helpers\Html;


$this->title = Yii::t('post','Create');
?>

<div class="page-posts no-padding">
    <div class="row">

        <div class="page page-post col-sm-12 col-xs-12 post-82">
            <div class="row profile-posts">




                <h3><?php echo Yii::t('post','Create post'); ?></h3>

                <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'picture')
        ->fileInput(['class' => 'btn btn-success',])
        ->label(Yii::t('post','upload picture < 2mb'),['class'=>'btn btn-success']); ?>

    <?= $form->field($model, 'description')
        ->textarea(['rows' => 4,
            'class' => 'form-control',
            'style' => 'font-weight:200;padding:0px 0px 0px 0px;margin: 0px 0px 0px 0px;width:70%',
        ])
        ->label(Yii::t('post','News respectfully post. Thank you.'), [
            'style' => 'font-weight:100;padding:0px 0px 0px 0px;margin: 10px 0px 0px 0px;width:70%',
        ]) ?>

                <?php echo Html::submitButton('Create', ['class' => 'btn btn-success']); ?>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
