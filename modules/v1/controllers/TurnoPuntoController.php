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
        $turnos = TurnoPunto::find()
            ->select(['dia', 'user_id'])
            ->with('user')
            ->where(['not', ['user_id' => null]])
            ->groupBy("dia, user_id")
            ->orderBy("dia")
            ->all();
        foreach ($turnos as $m) {
            $m->fieldsScenario = 'encargados';
        }
        return $turnos;
    }

    public function actionDisponiblePorDia() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $data->fecha = date('Y-m-d', strtotime($data->dia));
        $turnoPunto = TurnoPunto::find()->where(["dia" => $data->dia])->all();
        return $turnoPunto;
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'encargados' => ['get'],
                'disponible-por-dia' => ['post'],
            ],
        ];
        return $behaviors;
    }
}
