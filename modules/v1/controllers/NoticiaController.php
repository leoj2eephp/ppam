<?php

namespace app\modules\v1\controllers;

use app\models\Noticia;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Default controller for the `v1` module
 */
class NoticiaController extends ActiveController {

    public $modelClass = "app\models\Noticia";

    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            // $filters = Yii::$app->request->queryParams;
            $noticias = Noticia::find()->where("estado = 1")->orderBy(["fecha" => SORT_DESC])->all();
            return $noticias;
        } catch (\Exception $e) {
            Yii::error($e, __METHOD__);
            return Yii::$app->response->sendError(400, $e->getMessage());
        }
    }


    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index' => ['GET'],
            ],
        ];
        return $behaviors;
    }
}
