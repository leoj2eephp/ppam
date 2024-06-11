<?php

namespace app\controllers;

use app\models\Dias;
use app\models\Disponibilidad;
use app\models\DisponibilidadSearch;
use app\models\Turno;
use app\models\User;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DisponibilidadController implements the CRUD actions for Disponibilidad model.
 */
class DisponibilidadController extends BaseRbacController {
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
        $user = User::find()->where(["id" => $id])->one();
        $user->limitInfo();
        $model = Disponibilidad::find()->joinWith(["user", "turno"])
            ->where("user_id = :userId AND disponibilidad.estado = 1", [":userId" => $id])
            ->addOrderBy(["turno.orden" => SORT_ASC])
            ->all();
            
        $turnos_x_dia = [];
        $turnos = Turno::find()->with("asignacions")->orderBy("orden")->all();
        foreach ($turnos as $indice => $turno) {
            $turnos_x_dia[$indice] = ["turno_id" => $turno->id, "desde" => $turno->desde, "hasta" => $turno->hasta];
            foreach (Dias::getAll() as $keyDia => $dia) {
                $estado = 0;
                if (sizeof($model) > 0) {
                    foreach ($model as $dispo) {
                        if ($dispo->dia == $keyDia && $turno->id == $dispo->turno_id) {
                            $estado = 1;
                        }
                    }
                }
                $valores = ["keyDia" => $keyDia, "dia" => $dia, "estado" => $estado];
                $turnos_x_dia[$indice]["valores"][$keyDia] = $valores;
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            "user" => $user,
            "turnos_x_dia" => $turnos_x_dia,
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
                $disponibilidad->estado = $data->estado ? 1 : 0;
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
