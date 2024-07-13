<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */

use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hola <?= Html::encode($user->username) ?>,
Usa el link a continuación para que puedas restablecer tu contraseña:
<?= Html::a(Html::encode($resetLink), $resetLink) ?>