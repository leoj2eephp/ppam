<?php

namespace app\modules\v1\controllers;

use app\components\Helper;
use app\models\Asignacion;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Default controller for the `v1` module
 */
class AsignacionController extends ActiveController {

    public $modelClass = "app\models\Asignacion";

    public function actionCrearTurno() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData, true);

        $asignacion = new Asignacion();
        $asignacion->fecha = date('Y-m-d', strtotime($data["fecha"]));
        $asignacion->user_id1 = $data["userId1"];
        $asignacion->user_id2 = $data["userId2"];
        $asignacion->turno_id = $data["turnoId"];
        $asignacion->punto_id = $data["puntoId"];
        if ($asignacion->save()) {
            // Levantar notificación
            $mensaje = "Ha sido asignado a " . $asignacion->punto->nombre . " a las " . Helper::formatToHourMinute($asignacion->turno->desde) .
                " hrs. para el día " . Helper::formatToLocalDate($asignacion->fecha) . ". Toque aquí para más detalles.";
            Helper::sendNotificationPush2("Nuevo turno PPAM", $mensaje, $asignacion->user1->device_token);
            Helper::sendNotificationPush2("Nuevo turno PPAM", $mensaje, $asignacion->user2->device_token);
            return "OK";
        } else {
            return join(", ", $asignacion->firstErrors);
        }

        return "ERROR";
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionMisAsignaciones() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $asignaciones = Asignacion::find()->with(["user1", "user2"])
            ->where("user_id1 = :uid OR user_id2 = :uid", [":uid" => $data->id])->all();
        return $asignaciones;
    }

    public function actionMisProximasAsignaciones() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $asignaciones = Asignacion::find()->with(["user1", "user2"])
            ->where("(user_id1 = :uid OR user_id2 = :uid) AND fecha >= curdate()", [":uid" => $data->id])
            ->all();
        return $asignaciones;
    }

    public function actionConfirmReject() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $asignacion = Asignacion::find()->where("id = :id", [":id" => $data->idAsignacion])->one();
        if ($data->confirm1 !== null)
            $asignacion->confirmado1 = $data->confirm1;

        if ($data->confirm2 !== null)
            $asignacion->confirmado2 = $data->confirm2;

        if ($asignacion->save())
            return true;
        return false;
    }

    public function actionAsignacionesDelDia() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $asignaciones = Asignacion::find()->where("fecha = CURDATE()")->all();
        return $asignaciones;
    }

    public function actionAsignacionesPorDia() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents("php://input");
        $data = json_decode($postData);
        $data->fecha = date('Y-m-d', strtotime($data->fecha));
        $asignaciones = Asignacion::find()->where(["fecha" => $data->fecha])->all();
        return $asignaciones;
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'mis-asignaciones' => ['post'],
                'mis-proximas-asignaciones' => ['post'],
                'confirm-reject' => ['post'],
                'asignaciones-por-dia' => ['post'],
                'crear-turno' => ['post'],
            ],
        ];
        return $behaviors;
    }
}
