<?php
use yii\helpers\Html;
use frontend\models\User;
?>

<?php if ($list): ?>
    <?php foreach ($list as $item): ?>
        <p>Author:

            <?php echo Html::encode(User::getNickNameById($item['author_id'])); ?>
            <br>
            <?php echo Html::encode($item['text']); ?>
        </p>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>