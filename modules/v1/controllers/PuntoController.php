<?php

namespace app\modules\v1\controllers;

use app\models\Punto;
use app\models\TurnoPunto;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Default controller for the `v1` module
 */
class PuntoController extends ActiveController {

    public $modelClass = "app\models\Punto";

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionTurnosAsociados() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $turnoPunto = TurnoPunto::find()->where("punto_id = :puntoId", [":puntoId" => $data->punto_id])->all();
        return $turnoPunto;
        /* return array_filter($puntos, function($punto) {
            return $punto->turno;
        }); */
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'turnos-asociados' => ['post'],
            ],
        ];
        return $behaviors;
    }
}
