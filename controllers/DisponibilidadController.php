<?php

namespace app\controllers;

use app\models\Disponibilidad;
use app\models\DisponibilidadSearch;
use app\models\Turno;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DisponibilidadController implements the CRUD actions for Disponibilidad model.
 */
class DisponibilidadController extends Controller {
    /**
     * @inheritDoc
     */
    public function behaviors() {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Disponibilidad models.
     *
     * @return string
     */
    public function actionIndex() {
        $searchModel = new DisponibilidadSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Disponibilidad model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Disponibilidad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new Disponibilidad();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Disponibilidad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = Disponibilidad::find()->where("user_id = :userId", [":userId" => $id])->one();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $turnos = Turno::find()->with("asignacions")->orderBy("orden")->all();
        return $this->render('update', [
            'model' => $model,
            "id" => $id,
            "turnos" => $turnos,
        ]);
    }

    public function actionUpdateTurnoDia() {
        if (\Yii::$app->request->isAjax) {
            $data = json_decode($_POST["json"]);
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
    }

    /**
     * Deletes an existing Disponibilidad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Disponibilidad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Disponibilidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Disponibilidad::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
