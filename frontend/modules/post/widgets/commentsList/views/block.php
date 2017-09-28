<?php
use yii\helpers\Html;
?>
<?php foreach ($list as $item): ?>
    <p>
        <?php echo Html::encode($item['text']); ?>
    </p>
    <hr>
<?php endforeach;
