<?php

namespace app\modules\v1\controllers;

use app\models\Asignacion;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;

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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        $asignaciones = Asignacion::find()->with(["userId1", "userId2"])
                        ->where("user_id1 = :uid OR user_id2 = :uid", [":uid" => $data->id])->all();
        return $asignaciones;
    }

    public function actionMisAsignacionesDos() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);

        $dataProvider = new ActiveDataProvider([
            'query' => Asignacion::find()
                            ->innerJoinWith(['userId1', 'userId2'])
                            ->where("user_id1 = :uid OR user_id2 = :uid", [":uid" => $data->id]),
            'pagination' => [
                'pageSize' => 20, // Ajusta el tamaño de la paginación según tus necesidades
            ],
        ]);
    
        return $dataProvider;
    }

    /* public function extraFields()
    {
        return ['userId1', 'userId2'];
    } */

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'mis-asignaciones' => ['post'],
                'mis-asignaciones-dos' => ['post'],
            ],
        ];
        return $behaviors;
    }
}
