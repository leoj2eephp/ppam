<?php

namespace app\modules\v1\controllers;

use app\models\Dias;
use app\models\Disponibilidad;
use app\models\User;
use Yii;
use yii\db\Query;
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
        $disponibilidades = Disponibilidad::find()
            ->join("INNER JOIN", "turno t", "t.id = disponibilidad.turno_id")->where(
                "user_id = :userId",
                [":userId" => $data->userId]
            )->orderBy(["dia" => SORT_ASC, "t.orden" => SORT_ASC])->all();

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

    public function actionChangeAvailability() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $disponibilidad = Disponibilidad::find()->where(
            "user_id = :userId AND id = :dispoId",
            [":userId" => $data->userId, ":dispoId" => $data->dispoId]
        )->one();
        if ($disponibilidad != null) {
            $disponibilidad->estado = $data->estado;
            return $disponibilidad->save();
        } else {
            return false;
        }
    }

    public function actionByTurnDay() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents("php://input");
        $data = json_decode($postData);
        $disponibles = Disponibilidad::find()
            ->joinWith(["user", "turno"])
            ->where("turno_id = :tId AND dia = :dia AND disponibilidad.estado = 1 AND user.status = :status",
                 [":tId" => $data->turno_id, ":dia" => $data->dia, ":status" => User::STATUS_ACTIVE])
            ->orderBy(["user.nombre" => SORT_ASC])
            ->all();

        return $disponibles;
    }

    public function actionDias() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Dias::getIntDay("Viernes");
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'mi-disponibilidad' => ['post'],
                'update-turno-dia' => ['post'],
                'change-availability' => ['post'],
                "dias" => ["get"],
            ],
        ];
        return $behaviors;
    }
}
