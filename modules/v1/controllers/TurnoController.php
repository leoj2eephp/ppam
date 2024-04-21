<?php

namespace app\modules\v1\controllers;

use app\models\Asignacion;
use app\models\Turno;
use app\models\TurnoPunto;
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

    public function actionGetByPunto() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $turnosPunto = TurnoPunto::find()
            ->joinWith(["punto", "turno"])
            ->where(["punto_id" => $data->punto_id, "dia" => $data->dia])
            ->addOrderBy(["dia" => SORT_ASC, "turno.orden" => SORT_ASC])
            ->all();

        $asignaciones = Asignacion::find()
            ->where("fecha = :fecha AND
             punto_id = :punto_id", [":fecha" => $data->fecha, ":punto_id" => $data->punto_id])->all();
        $turnosPuntoDisponibles = [];

        $turnosIds = array_column($asignaciones, 'turno_id');
        foreach ($turnosPunto as $ua) {
            $found_key = array_search($ua->turno_id, $turnosIds);
            if (gettype($found_key) == "boolean") {
                $turnosPuntoDisponibles[] = $ua->toArray();
            }
        }
        
        return $turnosPuntoDisponibles;
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
