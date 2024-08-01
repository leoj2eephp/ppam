<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?php
            $form = ActiveForm::begin([
                'id' => 'logout-form',
                'action' => ['site/logout'],
                'method' => 'post',
            ]);

            if (!Yii::$app->user->isGuest) {
                echo Html::submitButton(
                    'Cerrar Sesión <i class="fas fa-sign-out-alt"></i>',
                    ['class' => 'btn btn-link logout']
                );
            }
            ActiveForm::end();
            ?>
        </li>
    </ul>

</nav>
<!-- /.navbar -->