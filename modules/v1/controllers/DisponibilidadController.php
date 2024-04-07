<?php

namespace app\modules\v1\controllers;

use app\models\Disponibilidad;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Default controller for the `v1` module
 */
class DisponibilidadController extends ActiveController {

    public $modelClass = "app\models\Disponibilidad";

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionMiDisponibilidad() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $disponibilidades = Disponibilidad::find()->where(
            "user_id = :userId",
            [":userId" => $data->userId]
        )->all();
        return $disponibilidades;
    }

    public function actionUpdateTurnoDia() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $disponibilidad = Disponibilidad::find()->where(
            "user_id = :userId AND turno_id = :turnoId AND dia = :dia",
            [":userId" => $data->userId, ":turnoId" => $data->turnoId, ":dia" => $data->dia]
        )->one();
        if ($disponibilidad != null) {
            $disponibilidad->estado = $data->estado;
            if ($disponibilidad->save()) return "OK";
            else return join(", ", $disponibilidad->getFirstErrors());
        } else {
            $disponibilidad = new Disponibilidad();
            $disponibilidad->user_id = $data->userId;
            $disponibilidad->turno_id = $data->turnoId;
            $disponibilidad->dia = $data->dia;
            $disponibilidad->estado = $data->estado ? 1 : 0;
            if ($disponibilidad->save()) return "OK";
            else return join(", ", $disponibilidad->getFirstErrors());
        }
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'mi-disponibilidad' => ['post'],
                'update-turno-dia' => ['post'],
            ],
        ];
        return $behaviors;
    }
}
