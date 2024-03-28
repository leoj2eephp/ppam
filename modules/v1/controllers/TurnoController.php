<?php

namespace app\modules\v1\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `v1` module
 */
class TurnoController extends ActiveController {

    public $modelClass = "app\models\Turno";

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionTesting() {
        return "HOLA";
    }
}
