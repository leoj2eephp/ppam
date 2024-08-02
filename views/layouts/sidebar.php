<?php

use yii\helpers\Url;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Url::to("/site/index") ?>" class="brand-link">
        <img src="<?= Yii::getAlias('@web') . '/images/logo.png' ?>" alt="PPAM" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">PPAM Osorno</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Inicio', 'header' => true],
                    ['label' => 'Inicio', 'url' => ['site/index'], 'icon' => 'home', 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Administración', 'header' => true],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Asignaciones', 'url' => ['asignacion/index'], 'icon' => 'calendar',
                        'visible' => Yii::$app->user->can("asignacion/index") || Yii::$app->user->can("admin")
                    ],
                    [
                        'label' => 'Puntos', 'url' => ['punto/index'], 'icon' => 'map-pin',
                        'visible' => Yii::$app->user->can("punto/index") || Yii::$app->user->can("admin")
                    ],
                    [
                        'label' => 'Turnos', 'url' => ['turno/index'], 'icon' => 'clock',
                        'visible' => Yii::$app->user->can("turno/index") || Yii::$app->user->can("admin")
                    ],
                    [
                        'label' => 'Usuarios', 'url' => ['user/index'], 'icon' => 'user',
                        'visible' => Yii::$app->user->can("user/index") || Yii::$app->user->can("admin")
                    ],
                    [
                        'label' => 'Mi Disponibilidad', 'url' => ['disponibilidad/update', "id" => Yii::$app->user->id], 'icon' => 'check',
                        'visible' => Yii::$app->user->can("disponibilidad/update") && !Yii::$app->user->can("admin")
                    ],
                    [
                        'label' => 'Encargados', 'url' => ['user/encargados'], 'icon' => 'info',
                        'visible' => Yii::$app->user->can("user/encargados") || Yii::$app->user->can("admin")
                    ], [
                        'label' => 'Noticias', 'url' => ['noticia/index'], 'icon' => 'newspaper',
                        'visible' => Yii::$app->user->can("noticia/index") || Yii::$app->user->can("admin")
                    ],
                    // ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>