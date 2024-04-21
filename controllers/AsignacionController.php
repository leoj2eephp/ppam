<?php

namespace app\controllers;

use app\components\Helper;
use app\models\EventJS;
use app\models\Asignacion;
use app\models\AsignacionSearch;
use app\models\Disponibilidad;
use app\models\Punto;
use app\models\Turno;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

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
            $e->title = $a->punto->nombre . ' ' . $a->turno->nombre . '
 - ' .
            $a->user1->nombreCompleto . '
 - ' .
            $a->user2->nombreCompleto;
            $e->start = $a->fecha . " " . $a->turno->desde;
            $e->end = $a->fecha . " " . $a->turno->hasta;
            $e->color = $a->punto->color;
            $e->url = Url::to(["/asignacion/update", "id" => $a->id]);
            // $e->customAttribute = Url::to(["/asignacion/update", "id" => $a->id]);
            $events[] = $e;
        }
        $turnos = Turno::findAll(["estado" => 1]);
        $puntos = Punto::find()->all();
        $usuarios = User::find()->where("status = :estado AND username != 'admin'", [":estado" => User::STATUS_ACTIVE])->all();

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
            // Levantar notificación
            $mensaje = "Ha sido asignado a " . $asignacion->punto->nombre . " a las " . Helper::formatToHourMinute($asignacion->turno->desde) .
                " hrs. para el día " . Helper::formatToLocalDate($asignacion->fecha) . ". Toque aquí para más detalles.";
            Helper::sendNotificationPush2("Nuevo turno PPAM", $mensaje, $asignacion->user1->device_token);
            Helper::sendNotificationPush2("Nuevo turno PPAM", $mensaje, $asignacion->user2->device_token);
            return $asignacion;
        } else {
            return join(", ", $asignacion->firstErrors);
        }

        return "ERROR";
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

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                return $this->redirect(["index"]);
            }
        }

        $turnos = Turno::findAll(["estado" => 1]);
        $puntos = Punto::find()->all();
        $usuariosActivos = User::find()
            ->select(["id", "username", "nombre", "apellido", "apellido_casada", "genero", "telefono", "email"])
            ->where("status = :estado AND username != 'admin'", [":estado" => User::STATUS_ACTIVE])->all();
        $dia = date("w", strtotime($model->fecha));

        $disponibles = Disponibilidad::find()
            ->joinWith(["user", "turno"])
            ->where("turno_id = :tId AND dia = :dia AND disponibilidad.estado = 1", [":tId" => $model->turno_id, ":dia" => $dia])
            ->groupBy("user_id")
            ->all();

        $usuariosD = [];
        $usuariosND = [];
        foreach ($disponibles as $d) {
            $usuariosD[] = $d->user;
        }

        $usuariosId = array_column($usuariosD, 'id');
        foreach ($usuariosActivos as $ua) {
            $found_key = array_search($ua->id, $usuariosId);
            if (gettype($found_key) == "boolean") {
                $usuariosND[] = $ua->toArray();
            }
        }

        $json = json_encode($usuariosND);
        if ($json === false) {
            throw new \Exception('Error encoding data: ' . json_last_error_msg());
        }


        return $this->render('update', [
            "model" => $model,
            "turnos" => $turnos,
            "puntos" => $puntos,
            "usuariosD" => $usuariosD,
            "usuariosND" => $json,
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
