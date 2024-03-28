<?php

namespace app\modules\v1\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `v1` module
 */
class PuntoController extends ActiveController {

    public $modelClass = "app\models\Punto";

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionTesting() {
        return "HOLA";
    }
}
