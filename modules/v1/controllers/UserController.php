<?php

namespace app\modules\v1\controllers;

use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;

/**
 * Default controller for the `v1` module
 */
class UserController extends ActiveController {

    public $modelClass = "app\models\User";

    public function actionLogin() {
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
    
        $user = User::findByUsername($data->username);
        $model = new LoginForm();
        $model->username = $data->username;
        $model->password = $data->password;
        if ($model->login()) {
            $token = Yii::$app->security->generateRandomString() . '_' . time();
            $user->access_token = $token;
            $user->save();
            $user->limitInfo();
            return [
                'status' => 'success',
                'token' => $token,
                'user' => $user,
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Usuario y/o contraseña incorrectos',
            ];
        }
    }

    public function actionSaveToken() {
        $postData = file_get_contents('php://input');
        $data = json_decode($postData);
    
        $user = User::findOne($data->id);
        $user->device_token = $data->token;
        return $user->save();
    }

    public function actionUpdateCondicion() {
        $postData = file_get_contents("php://input");
        $data = json_decode($postData);

        $user = User::findOne($data->id);
        $user->condicion_especial = $data->condicion;
        return $user->save();
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'login' => ['post'],
            ],
        ];
        return $behaviors;
    }
}
