<?php

namespace app\modules\v1\controllers;

use app\models\Asignacion;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
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
        $asignaciones = Asignacion::find()->with(["userId1", "userId2"])
            ->where("user_id1 = :uid OR user_id2 = :uid", [":uid" => $data->id])->all();
        return $asignaciones;
    }

    public function actionConfirmReject() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $asignacion = Asignacion::find()->where("id = :id", [":id" => $data->idAsignacion])->one();
        if ($data->confirm1 != null) $asignacion->confirmado1 = (int) $data->confirm1;
        if ($data->confirm2 != null) $asignacion->confirmado2 = (int) $data->confirm2;

        if ($asignacion->save()) return true;
        return false;
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'mis-asignaciones' => ['post'],
                'confirm-reject' => ['post'],
            ],
        ];
        return $behaviors;
    }
}
