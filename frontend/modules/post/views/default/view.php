<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
use yii\web\JqueryAsset;

?>
<div class="post-default-index">

    <div class="row">

        <div class="col-md-12">
            <?php echo Html::encode($post->filename); ?>
        </div>

        <div class="col-md-12">
            <?php echo Html::encode($post->description); ?>
        </div>

    </div>
</div>

