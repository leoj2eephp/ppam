<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use Exception;
use app\components\Mailer;

class PasswordResetRequestForm extends Model {
    public $email;

    public function rules() {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'No existe ningún usuario con este correo electrónico.'
            ],
        ];
    }

    

    public function sendEmail() {
        try {
            $user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
            ]);

            if (!$user) {
                return false;
            }

            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
                if (!$user->save()) {
                    return false;
                }
            }

            $asunto = "Restablecer contraseña PPAM";
            $view = '/mail/passwordResetTokenHtml';
            $params = ['user' => $user];
            $result = Mailer::send($user->email, $asunto, $view, $params);
            return $result == "OK" ? true : false;
        } catch (Exception $ex) {
            Yii::error("Error al enviar el correo de restablecimiento de contraseña: " . $ex->getMessage());
            return false;
        }
    }
}
