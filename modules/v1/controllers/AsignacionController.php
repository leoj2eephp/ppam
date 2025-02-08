<?php

namespace app\modules\v1\controllers;

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
            ],
        ];
        return $behaviors;
    }
}
