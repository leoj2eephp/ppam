<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller {
    public function actionInit() {
        $auth = Yii::$app->authManager;

        // Añadir permisos
        $manageTurnos = $auth->createPermission('manageTurns');
        $manageTurnos->description = 'Administrar turnos';
        $auth->add($manageTurnos);

        $managePuntos = $auth->createPermission('manageStands');
        $managePuntos->description = 'Administrar puntos';
        $auth->add($managePuntos);

        $manageDisponibilidad = $auth->createPermission('manageAvailability');
        $manageDisponibilidad->description = 'Administrar disponibilidad';
        $auth->add($manageDisponibilidad);

        $manageAsignaciones = $auth->createPermission('manageAssignments');
        $manageAsignaciones->description = 'Administrar asignaciones';
        $auth->add($manageAsignaciones);

        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Administrar users';
        $auth->add($manageUsers);

        // Añadir roles
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $manageTurnos);
        $auth->addChild($admin, $managePuntos);
        $auth->addChild($admin, $manageDisponibilidad);
        $auth->addChild($admin, $manageAsignaciones);
        $auth->addChild($admin, $manageUsers);

        $supervisor = $auth->createRole('supervisor');
        $auth->add($supervisor);
        // $auth->addChild($supervisor, $manageTurnos);
        // $auth->addChild($supervisor, $managePuntos);
        // $auth->addChild($supervisor, $manageDisponibilidad);
        $auth->addChild($supervisor, $manageAsignaciones);

        $usuario = $auth->createRole('usuario');
        $auth->add($usuario);
        /* $auth->addChild($usuario, $manageDisponibilidad);
        $auth->addChild($usuario, $manageAsignaciones); */

        // Asignar roles a usuarios (1, 2, 3 son IDs de usuarios)
        $auth->assign($admin, 1);
        $auth->assign($supervisor, 2);
        $auth->assign($usuario, 3);
    }
}
