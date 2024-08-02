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
        <?php
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $bienvenido = "Bienvenid";
            if ($user->genero == 1) {
                $bienvenido .= "o " . $user->nombreCompleto;
            } else {
                $bienvenido .= "a " . $user->nombreCompleto;
            }
            echo "<h3>$bienvenido</h3>";
        }
        ?>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?= Html::a(
                'Mi Cuenta <i class="fas fa-user-circle"></i>',
                ['user/cuenta'],
                ['class' => 'btn btn-link user']
            );
            ?>
        </li>
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