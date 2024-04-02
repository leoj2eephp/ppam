<?php

namespace app\controllers;

use app\models\EventJS;
use app\models\Asignacion;
use app\models\AsignacionSearch;
use app\models\Punto;
use app\models\Turno;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AsignacionController implements the CRUD actions for Asignacion model.
 */
class AsignacionController extends Controller {
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
                        // 'crear-turno' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action) {
        if ($action->id == "crear-turno") {
            $this->enableCsrfValidation = false;
        }
        return  parent::beforeAction($action);
    }

    /**
     * Lists all Asignacion models.
     *
     * @return string
     */
    public function actionIndex() {
        $searchModel = new AsignacionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // CARGAR TURNOS
        $asignaciones = Asignacion::find()
            ->where("fecha BETWEEN (SELECT ADDDATE( ADDDATE( LAST_DAY(NOW()), 1), INTERVAL -1 MONTH)) AND ADDDATE( LAST_DAY(NOW()), 1)")
            ->all();
        $events = array();
        foreach ($asignaciones as $a) {
            $e = new EventJS();
            $e->id = $a->id;
            $e->title = $a->punto->nombre . ' ' . $a->turno->nombre;
            $e->start = $a->fecha . " " . $a->turno->desde;
            $e->end = $a->fecha . " " . $a->turno->hasta;
            $e->color = $a->punto->color;
            $events[] = $e;
        }
        $turnos = Turno::findAll(["estado" => 1]);
        $puntos = Punto::find()->all();
        $usuarios = User::find(["state" => User::STATUS_ACTIVE])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            "events" => $events,
            "turnos" => $turnos,
            "puntos" => $puntos,
            "usuarios" => $usuarios
        ]);
    }

    public function actionCrearTurno() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData, true);

        $asignacion = new Asignacion();
        $asignacion->fecha = $data["fecha"];
        $asignacion->user_id1 = $data["usuario1"];
        $asignacion->user_id2 = $data["usuario2"];
        $asignacion->turno_id = $data["turno"];
        $asignacion->punto_id = $data["punto"];

        if ($asignacion->save()) {
            return "OK";
        } else {
            return join(", ", $asignacion->firstErrors);
        }

        return "ERROR";
    }

    /**
     * Displays a single Asignacion model.
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
     * Creates a new Asignacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new Asignacion();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Asignacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Asignacion model.
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
     * Finds the Asignacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Asignacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Asignacion::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
