<?php
/* @var $this yii\web\View */
/* @var $model app\models\User */

use yii\helpers\Html;
$siteName = 'PPAM';
$siteUrl = Yii::$app->params['siteUrl'];
?>
<p>Estimado hermano:</p>
<p>Se ha creado una cuenta para usted en ppamosorno.org. Sus datos son los siguientes:</p>
3
<table>
    <tr>
        <td>Nombre de Usuario:</td>
        <td><?= Html::encode($model->username) ?></td>
    </tr>
    <tr>
        <td>Correo electrónico:</td>
        <td><?= Html::encode($model->email) ?></td>
    </tr>
</table>

<p>Atentamente,</p>
<?= Html::a(Html::encode($siteUrl), $siteUrl) ?>