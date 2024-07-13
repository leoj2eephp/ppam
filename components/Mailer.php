<?php

namespace app\components;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Yii;

class Mailer {

    public static $Host = 'smtp.hostinger.com';
    public static $SMTPAuth = true;
    public static $Username = 'contacto@ppamosorno.org';
    public static $Password = '';
    public static $SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    public static $Port = 465; // Usar 587 para TLS

    public static function send($destinatario, $asunto, $view, $params) {
        $mail = new PHPMailer(true);

        try {
            // Configurar PHPMailer
            $mail->isSMTP();
            $mail->Host =     Mailer::$Host;
            $mail->SMTPAuth = Mailer::$SMTPAuth;
            $mail->Username = Mailer::$Username;
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = Mailer::$SMTPSecure;
            $mail->Port =     Mailer::$Port;
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'html';

            $mail->setFrom(Mailer::$Username, 'Remitente');
            $mail->addAddress($destinatario);
            $mail->isHTML(true);
            $mail->Subject = mb_encode_mimeheader($asunto, 'UTF-8', 'B');

            ob_start();
            $body = Yii::$app->controller->renderPartial($view, $params);
            ob_clean();
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);

            if ($mail->send()) {
                return "OK";
            } else {
                throw new Exception('Error al enviar el correo: ' . $mail->ErrorInfo);
            }
        } catch (Exception $e) {
            return "Error al enviar el correo: {$e->getMessage()}";
        } finally {
            ob_end_clean();
        }    
    }
}
