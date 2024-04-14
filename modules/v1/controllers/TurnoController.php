<?php

namespace app\modules\v1\controllers;

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

        return $turnosPunto;
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
