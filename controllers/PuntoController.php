<?php

namespace app\controllers;

use app\models\Dias;
use app\models\Punto;
use app\models\PuntoSearch;
use app\models\Turno;
use app\models\TurnoPunto;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PuntoController implements the CRUD actions for Punto model.
 */
class PuntoController extends BaseRbacController {
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

    public function beforeAction($action) {
        if ($action->id == "update-turnos" || $action->id == "sync-all-turns") {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Punto models.
     *
     * @return string
     */
    public function actionIndex() {
        $searchModel = new PuntoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Punto model.
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
     * Creates a new Punto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new Punto();

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
     * Updates an existing Punto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateTurnos($id) {
        if ($this->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $postData = file_get_contents('php://input');
            $data = json_decode($postData);
            $model = new TurnoPunto();
            $model->punto_id = $data->punto_id;
            $model->turno_id = (int) $data->turno_id;
            $model->dia = (int) $data->dia;
            if ($model->save()) {
                $turno = Turno::find()->where(["id" => $model->turno_id])->one();
                return ["data" => $turno, "status" => "OK"];
            } else {
                return ["data" => join(", ", $model->getFirstErrors()), "status" => "ERROR"];
            }
        }
        $model = $this->findModel($id);
        /* $turnosPunto = TurnoPunto::find()->with(["turno", "punto"])
            ->where("punto_id = :punto_id", [":punto_id" => $id])->orderBy(["dia" => SORT_ASC])->all();*/
        $turnosPunto = TurnoPunto::find()
            ->join("INNER JOIN", "punto p", "p.id = turno_punto.punto_id")
            ->join("INNER JOIN", "turno t", "t.id = turno_punto.turno_id")
            ->where("punto_id = :punto_id", [":punto_id" => $id])
            ->orderBy(["dia" => SORT_ASC, "t.orden" => SORT_ASC])->all();

        $turnos = Turno::find()->all();
        return $this->render('update_turnos', [
            'model' => $model,
            "turnos" => $turnos,
            "turnosPunto" => $turnosPunto,
        ]);
    }

    public function actionSyncAllTurns() {
        if ($this->request->isPost) {
            $puntoId = $_POST["puntoId"];
            TurnoPunto::deleteAll(["punto_id" => $puntoId]);
            foreach (Dias::getAll() as $dia) {
                $turnos = Turno::find()->orderBy("orden")->all();
                foreach ($turnos as $t) {
                    $turnoPunto = new TurnoPunto();
                    $turnoPunto->punto_id = $puntoId;
                    $turnoPunto->turno_id = $t->id;
                    $turnoPunto->dia = Dias::getIntDay($dia);
                    $turnoPunto->save();
                }
            }
            return $this->redirect(["update-turnos", "id" => $puntoId]);
        }
    }

    public function actionDeleteTurnoPunto($pId, $tId, $dia) {
        $turnoPunto = TurnoPunto::find()
            ->where(
                "punto_id = :pId AND turno_id = :tId AND dia = :dia",
                [":pId" => $pId, ":tId" => $tId, ":dia" => $dia]
            )->one();
        if ($turnoPunto->delete()) return $this->redirect(["update-turnos", "id" => $pId]);
    }

    /**
     * Deletes an existing Punto model.
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
     * Finds the Punto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Punto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Punto::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
