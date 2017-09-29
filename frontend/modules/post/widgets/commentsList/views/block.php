<?php
use yii\helpers\Html;
?>

<?php if ($list): ?>
    <?php foreach ($list as $item): ?>
        <p>
            <?php echo Html::encode($item['text']); ?>
        </p>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>