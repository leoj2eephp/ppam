<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hola <?= $user->username ?>,

Usa el link a continuación para que puedas restablecer tu contraseña:

<?= $resetLink ?>