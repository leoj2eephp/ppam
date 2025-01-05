<?php

namespace app\controllers;

use app\models\Asignacion;
use app\models\LoginForm;
use app\models\Noticia;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\User;
use InvalidArgumentException;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $noticias = Noticia::find()->where("estado = 1")->orderBy("fecha DESC")->all();
        $asignaciones = Asignacion::find()->where(
            "(user_id1 = :id OR user_id2 = :id) and fecha >= CURDATE()",
            [":id" => Yii::$app->user->id]
        )->all();
        return $this->render('index', ["noticias" => $noticias, "asignaciones" => $asignaciones]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        // $this->layout = 'blank';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $id = Yii::$app->user->id;
            if (!User::updateLastSession($id)) {
                Yii::$app->session->setFlash('error', 'No se pudo registrar inicio de sesión.');
            }
            return $this->goBack();
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $result = $model->sendEmail();
            if ($result) {
                Yii::$app->session->setFlash('success', 'Email enviado con instrucciones para restablecer contraseña.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo enviar el correo electrónico.');
            }
        }
    
        return $this->render('passwordReset', [
            'model' => $model,
        ]);
    }    

    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('updatePassword', [
            'model' => $model,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
