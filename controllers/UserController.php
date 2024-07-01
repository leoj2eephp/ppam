<?php

namespace app\controllers;

use app\models\TurnoPunto;
use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseRbacController {
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new User();
        $model->scenario = "create";

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->createUser();
                return $this->redirect(["index"]);
            }
        } else {
            $model->loadDefaultValues();
            $model->rol = "usuario";
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($model->id);
        $roleNames = array_keys($roles);
        $model->rol = $roles[$roleNames[0]]->name;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updateRol();
            $model->save();
            return $this->redirect(["index"]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionEncargados() {
        $model = TurnoPunto::find()
            ->select(['dia', 'MIN(turno_id) as turno_id', 'MIN(punto_id) as punto_id', 'MIN(user_id) as user_id'])
            ->with(['user'])
            ->groupBy('dia')
            ->orderBy(['dia' => SORT_ASC])
            ->all();
    
        $encargados = User::find()
            ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->where(['auth_assignment.item_name' => "supervisor"])
            ->all();
    
        return $this->render("encargados", ["model" => $model, "encargados" => $encargados]);
    }    

    public function actionUpdateEncargadoDia() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
        try {
            if (isset($data->dia) && isset($data->encargado)) {
                $resultado = TurnoPunto::updateAll(
                    ['user_id' => $data->encargado],
                    'dia = :dia',
                    [':dia' => $data->dia]
                );

                if ($resultado > 0) {
                    $user = $this->findModel($data->encargado);
                    return ['status' => 'ok', 'message' => 'Encargado actualizado correctamente', "user" => $user];
                } else {
                    return ['status' => 'error', 'message' => 'No se encontraron registros para actualizar'];
                }
            } else {
                return ['status' => 'error', 'message' => 'Datos incompletos'];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
