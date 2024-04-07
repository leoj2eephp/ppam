<?php

namespace app\modules\v1\controllers;

use app\models\Turno;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Default controller for the `v1` module
 */
class TurnoController extends ActiveController {

    public $modelClass = "app\models\Turno";

    public function actionOrden() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $turnos = Turno::find()->orderBy("orden")->all();
        return $turnos;
    }
    
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'orden' => ['get'],
            ],
        ];
        return $behaviors;
    }
    
}
