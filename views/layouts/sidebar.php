<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">PPAM Osorno</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Administración', 'header' => true],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Asignaciones', 'url' => ['asignacion/index'], 'icon' => 'calendar', 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Puntos', 'url' => ['punto/index'], 'icon' => 'map-pin', 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Turnos', 'url' => ['turno/index'], 'icon' => 'clock', 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Usuarios', 'url' => ['user/index'], 'icon' => 'user', 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Disponibilidad', 'url' => ['disponibilidad/index'], 'icon' => 'check', 'visible' => false],
                    // ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>