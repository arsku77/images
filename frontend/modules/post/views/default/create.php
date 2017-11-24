<?php
/* @var $this yii\web\View */
/* @var $model frontend\modules\post\models\forms\PostForm */

use Yii;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = Yii::t('post','Create');
?>

<div class="post-default-index">

    <h3><?php echo Yii::t('post','Create post'); ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'picture')->fileInput()
        ->label(Yii::t('post','upload picture < 2mb')); ?>

    <?= $form->field($model, 'description')
        ->textarea(['rows' => 4,
            'class' => 'form-control',
            'style' => 'font-weight:200;padding:0px 0px 0px 0px;margin: 0px 0px 0px 0px;width:70%',
        ])
        ->label(Yii::t('post','News respectfully post. Thank you.'), [
            'style' => 'font-weight:100;padding:0px 0px 0px 0px;margin: 10px 0px 0px 0px;width:70%',
        ]) ?>

    <?php echo Html::submitButton('Create'); ?>

    <?php ActiveForm::end(); ?>

</div>