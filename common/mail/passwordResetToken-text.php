<?php

/* @var $this yii\web\View */
/* @var $user \frontend\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/default/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
