<?php

namespace app\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller {
    public function actionInit() {
        $auth = Yii::$app->authManager;

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $voluntario = $auth->createRole('voluntario');
        $auth->add($voluntario);
        
        $createUser = $auth->createPermission('user/crear-usuario');
        $createUser->description = 'Crea un usuario';
        $auth->add($createUser);

        $createPunto = $auth->createPermission('crearPunto');
        $createPunto->description = 'Crear un Punto';
        $auth->add($createPunto);

        $createTurno = $auth->createPermission('crearTurno');
        $createTurno->description = 'Crear un Punto';
        $auth->add($createTurno);

        $createAsignacion = $auth->createPermission('crearAsignacion');
        $createAsignacion->description = 'Crear un Asignación';
        $auth->add($createAsignacion);

        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $createPunto);
        $auth->addChild($admin, $createTurno);
        $auth->addChild($admin, $createAsignacion);
        // Se pueden sumar todos los permisos de otro rol..
        // $auth->addChild($admin, $author);

        // asigna roles a usuarios. 1 y 2 son IDs devueltos por IdentityInterface::getId()
        // usualmente implementado en tu modelo User.
        $auth->assign($admin, 1);
        $auth->assign($voluntario, 3);
        $auth->assign($voluntario, 4);
        $auth->assign($voluntario, 5);
        $auth->assign($voluntario, 6);
    }
}
