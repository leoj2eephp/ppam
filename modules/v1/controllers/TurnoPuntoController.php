<?php

namespace app\modules\v1\controllers;

use app\models\TurnoPunto;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Default controller for the `v1` module
 */
class TurnoPuntoController extends ActiveController {

    public $modelClass = "app\models\TurnoPunto";

    public function actionEncargados() {
         Yii::$app->response->format = Response::FORMAT_JSON;
        $turnos = TurnoPunto::find()->groupBy("dia")->orderBy("dia")->all();
        foreach ($turnos as $m) {
            $m->fieldsScenario = 'encargados';
        }
        return $turnos;
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'encargados' => ['get'],
            ],
        ];
        return $behaviors;
    }
}
