<?php

namespace app\modules\v1\controllers;

use app\models\Asignacion;
use app\models\User;
use Yii;
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
        $asignaciones = Asignacion::find()
                        ->where("user_id1 = :uid OR user_id2 = :uid", [":uid" => $data->id])->all();
        return $asignaciones;
    }

    public function extraFields()
    {
        return ['userId1', 'userId2'];
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'mis-asignaciones' => ['post'],
            ],
        ];
        return $behaviors;
    }
}
