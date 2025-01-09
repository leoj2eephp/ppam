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
use Exception;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;

/**
 * AsignacionController implements the CRUD actions for Asignacion model.
 */
class AsignacionController extends BaseRbacController {
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
                        'confirm-reject' => ['POST'],
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
            ->where("fecha BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() + INTERVAL 30 DAY;")
            ->all();
        $events = array();
        foreach ($asignaciones as $a) {
            $estado1 = ["S/C", "Sin confirmar"];
            $estado2 = ["S/C", "Sin confirmar"];
            if (isset($a->confirmado1)) {
                $estado1 = $a->confirmado1 ? ["Conf.", "Confirmado"] : ["Rech.", "Rechazado"];
            }
            if (isset($a->confirmado2)) {
                $estado2 = $a->confirmado2 ? ["Conf.", "Confirmado"] : ["Rech.", "Rechazado"];
            }
            $e = new EventJS();
            $e->id = $a->id;
            // $e->title =  . '\n'  ' ' . $a->user1->nombreCompleto . ' ' . $a->user2->nombreCompleto;
            // $e->title = $a->turno->nombre . " - " . $a->punto->nombre;
            $e->description = '<div style="text-align: center;"><b>' . $a->punto->nombre . "-" . $a->turno->nombre . '</b></div>' .
                $e->description = '<div style="text-align: center;"><b>' . Helper::formatToHourMinute($a->turno->desde) .
                " - " . Helper::formatToHourMinute($a->turno->hasta) . '</b></div>' .
                '- ' . $a->user1->nombreCompleto . " (" . $estado1[0] . ")<br>" .
                '- ' . $a->user2->nombreCompleto . " (" . $estado2[0] . ")<br>";
            $e->start = $a->fecha . " " . $a->turno->desde;
            $e->end = $a->fecha . " " . $a->turno->hasta;
            $e->color = $a->punto->color;
            $e->customAttribute = $this->getCustomAttribute($a->user1->nombreCompleto, $estado1[1]);
            $e->customAttribute .= $this->getCustomAttribute($a->user2->nombreCompleto, $estado2[1]);
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

    protected function getCustomAttribute($nombre, $estado) {
        $color = "";
        switch ($estado) {
            case "Sin confirmar":
                $color = "text-primary";
                break;
            case "Confirmado":
                $color = "text-success";
                break;
            case "Rechazado":
                $color = "text-danger";
                break;
        }

        return "$nombre (<span class='$color text-bold'>$estado</span>)<br />";
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

        if ($this->request->isPost) {
            $asignadoOld1 = $model->user_id1;
            $asignadoOld2 = $model->user_id2;
            if ($model->load($this->request->post())) {
                $model->confirmado1 = $model->confirmado1 == -1 ? null : $model->confirmado1;
                $model->confirmado2 = $model->confirmado2 == -1 ? null : $model->confirmado2;
                if ($model->save()) {
                    $mensaje = "Ha sido asignado a " . $model->punto->nombre . " a las " . Helper::formatToHourMinute($model->turno->desde) .
                        " hrs. para el día " . Helper::formatToLocalDate($model->fecha) . ". Toque aquí para más detalles.";
                    if ($asignadoOld1 != $model->user_id1) {
                        Helper::sendNotificationPush2("Nuevo turno PPAM", $mensaje, $model->user1->device_token);
                    }

                    if ($asignadoOld2 != $model->user_id2) {
                        Helper::sendNotificationPush2("Nuevo turno PPAM", $mensaje, $model->user2->device_token);
                    }
                    return $this->redirect(["index"]);
                }
            }
        }

        $turnos = Turno::findAll(["estado" => 1]);
        $puntos = Punto::find()->all();
        $usuariosActivos = User::find()
            ->select(["id", "username", "nombre", "apellido", "apellido_casada", "genero", "telefono", "email"])
            ->where("status = :estado AND username != 'admin'", [":estado" => User::STATUS_ACTIVE])
            ->orderBy(["user.nombre" => SORT_ASC])->all();
        $dia = date("w", strtotime($model->fecha));

        $disponibles = Disponibilidad::find()
            ->joinWith(["user", "turno"])
            ->where("turno_id = :tId AND dia = :dia AND disponibilidad.estado = 1", [":tId" => $model->turno_id, ":dia" => $dia])
            ->orderBy(["user.nombre" => SORT_ASC])
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

    public function actionConfirmReject() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $data = json_decode(file_get_contents('php://input'));
            if (!isset($data->id) || !isset($data->confirm)) {
                return ['success' => false, 'message' => 'Datos insuficientes'];
            }

            $join = $data->confirm == 1 ? "user1" : "user2";
            $asignacion = Asignacion::find()->with($join)->where(['id' => $data->id])->one();
            if (isset($asignacion)) {
                if ((($join == "user1" && $asignacion->user1->id !== Yii::$app->user->id) ||
                        ($join == "user2" && $asignacion->user2->id !== Yii::$app->user->id)) &&
                    !isset($data->supervisor)
                )
                    return ['success' => false, 'message' => 'No autorizado o asignación no encontrada'];
            }

            if (isset($data->confirm)) {
                if ($data->confirm == 1) $asignacion->confirmado1 = $data->estado;
                if ($data->confirm == 2) $asignacion->confirmado2 = $data->estado;
            } else {
                return ['success' => false, 'message' => 'Datos de confirmación inválidos'];
            }
            $participacion = $data->estado == 1 ? "Participación confirmada" : "Participación rechazada";
            return $asignacion->save()
                ? ['success' => true, 'message' => $participacion]
                : ['success' => false, 'message' => 'No se pudo guardar la asignación'];
        } catch (Exception $e) {
            Yii::error("Error en actionConfirmReject: " . $e->getMessage(), __METHOD__);
            return ['success' => false, 'message' => 'Ocurrió un error. ' .  $e->getMessage()];
        }
    }

    /**
     * Deletes an existing Asignacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete() {
        $id = $this->request->post("id") ?? null;
        if ($id !== null) {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        }

        return $this->redirect(Yii::$app->request->referrer);
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
