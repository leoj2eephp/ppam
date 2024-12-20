<?php

namespace app\components;

use Exception;
use Google;

/**
 * Validates that a rut has a correct format and is a valid rut 
 *
 * @author fvasquez
 */
class Helper {

    public static function removeFilesFromDirectory($dirPath) {
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                //self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public static function fixDateFormat($date) {
        $arr = explode('-', $date);
        if (count($arr) == 3)
            return "$arr[2]-$arr[1]-$arr[0]";
        return "";
    }

    public static function removeSlashes($input) {
        $input = str_replace("\\", "", $input);
        $input = str_replace(PHP_EOL, "\\n", $input);
        $input = str_replace("'", "", $input);
        return $input;
    }

    public static function removeSlashesAndAddBlanks($input, $blanks) {
        $input = str_replace("\\", "", $input);
        $input = str_replace("'", "", $input);
        $largo = strlen($input);
        for ($i = $largo; $i < $blanks; $i++) {
            $input = $input . " ";
        }
        return $input;
    }

    public static function base64UrlEncode($inputStr) {
        return strtr(base64_encode($inputStr), '+/=', '-_,');
    }

    public static function base64UrlDecode($inputStr) {
        return base64_decode(strtr($inputStr, '-_,', '+/='));
    }

    public static function remove_accents($string) {
        if (!preg_match('/[\x80-\xff]/', $string))
            return $string;

        $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(195) . chr(128) => 'A',
            chr(195) . chr(129) => 'A',
            chr(195) . chr(130) => 'A',
            chr(195) . chr(131) => 'A',
            chr(195) . chr(132) => 'A',
            chr(195) . chr(133) => 'A',
            chr(195) . chr(135) => 'C',
            chr(195) . chr(136) => 'E',
            chr(195) . chr(137) => 'E',
            chr(195) . chr(138) => 'E',
            chr(195) . chr(139) => 'E',
            chr(195) . chr(140) => 'I',
            chr(195) . chr(141) => 'I',
            chr(195) . chr(142) => 'I',
            chr(195) . chr(143) => 'I',
            chr(195) . chr(145) => 'N',
            chr(195) . chr(146) => 'O',
            chr(195) . chr(147) => 'O',
            chr(195) . chr(148) => 'O',
            chr(195) . chr(149) => 'O',
            chr(195) . chr(150) => 'O',
            chr(195) . chr(153) => 'U',
            chr(195) . chr(154) => 'U',
            chr(195) . chr(155) => 'U',
            chr(195) . chr(156) => 'U',
            chr(195) . chr(157) => 'Y',
            chr(195) . chr(159) => 's',
            chr(195) . chr(160) => 'a',
            chr(195) . chr(161) => 'a',
            chr(195) . chr(162) => 'a',
            chr(195) . chr(163) => 'a',
            chr(195) . chr(164) => 'a',
            chr(195) . chr(165) => 'a',
            chr(195) . chr(167) => 'c',
            chr(195) . chr(168) => 'e',
            chr(195) . chr(169) => 'e',
            chr(195) . chr(170) => 'e',
            chr(195) . chr(171) => 'e',
            chr(195) . chr(172) => 'i',
            chr(195) . chr(173) => 'i',
            chr(195) . chr(174) => 'i',
            chr(195) . chr(175) => 'i',
            chr(195) . chr(177) => 'n',
            chr(195) . chr(178) => 'o',
            chr(195) . chr(179) => 'o',
            chr(195) . chr(180) => 'o',
            chr(195) . chr(181) => 'o',
            chr(195) . chr(182) => 'o',
            chr(195) . chr(182) => 'o',
            chr(195) . chr(185) => 'u',
            chr(195) . chr(186) => 'u',
            chr(195) . chr(187) => 'u',
            chr(195) . chr(188) => 'u',
            chr(195) . chr(189) => 'y',
            chr(195) . chr(191) => 'y',
            // Decompositions for Latin Extended-A
            chr(196) . chr(128) => 'A',
            chr(196) . chr(129) => 'a',
            chr(196) . chr(130) => 'A',
            chr(196) . chr(131) => 'a',
            chr(196) . chr(132) => 'A',
            chr(196) . chr(133) => 'a',
            chr(196) . chr(134) => 'C',
            chr(196) . chr(135) => 'c',
            chr(196) . chr(136) => 'C',
            chr(196) . chr(137) => 'c',
            chr(196) . chr(138) => 'C',
            chr(196) . chr(139) => 'c',
            chr(196) . chr(140) => 'C',
            chr(196) . chr(141) => 'c',
            chr(196) . chr(142) => 'D',
            chr(196) . chr(143) => 'd',
            chr(196) . chr(144) => 'D',
            chr(196) . chr(145) => 'd',
            chr(196) . chr(146) => 'E',
            chr(196) . chr(147) => 'e',
            chr(196) . chr(148) => 'E',
            chr(196) . chr(149) => 'e',
            chr(196) . chr(150) => 'E',
            chr(196) . chr(151) => 'e',
            chr(196) . chr(152) => 'E',
            chr(196) . chr(153) => 'e',
            chr(196) . chr(154) => 'E',
            chr(196) . chr(155) => 'e',
            chr(196) . chr(156) => 'G',
            chr(196) . chr(157) => 'g',
            chr(196) . chr(158) => 'G',
            chr(196) . chr(159) => 'g',
            chr(196) . chr(160) => 'G',
            chr(196) . chr(161) => 'g',
            chr(196) . chr(162) => 'G',
            chr(196) . chr(163) => 'g',
            chr(196) . chr(164) => 'H',
            chr(196) . chr(165) => 'h',
            chr(196) . chr(166) => 'H',
            chr(196) . chr(167) => 'h',
            chr(196) . chr(168) => 'I',
            chr(196) . chr(169) => 'i',
            chr(196) . chr(170) => 'I',
            chr(196) . chr(171) => 'i',
            chr(196) . chr(172) => 'I',
            chr(196) . chr(173) => 'i',
            chr(196) . chr(174) => 'I',
            chr(196) . chr(175) => 'i',
            chr(196) . chr(176) => 'I',
            chr(196) . chr(177) => 'i',
            chr(196) . chr(178) => 'IJ',
            chr(196) . chr(179) => 'ij',
            chr(196) . chr(180) => 'J',
            chr(196) . chr(181) => 'j',
            chr(196) . chr(182) => 'K',
            chr(196) . chr(183) => 'k',
            chr(196) . chr(184) => 'k',
            chr(196) . chr(185) => 'L',
            chr(196) . chr(186) => 'l',
            chr(196) . chr(187) => 'L',
            chr(196) . chr(188) => 'l',
            chr(196) . chr(189) => 'L',
            chr(196) . chr(190) => 'l',
            chr(196) . chr(191) => 'L',
            chr(197) . chr(128) => 'l',
            chr(197) . chr(129) => 'L',
            chr(197) . chr(130) => 'l',
            chr(197) . chr(131) => 'N',
            chr(197) . chr(132) => 'n',
            chr(197) . chr(133) => 'N',
            chr(197) . chr(134) => 'n',
            chr(197) . chr(135) => 'N',
            chr(197) . chr(136) => 'n',
            chr(197) . chr(137) => 'N',
            chr(197) . chr(138) => 'n',
            chr(197) . chr(139) => 'N',
            chr(197) . chr(140) => 'O',
            chr(197) . chr(141) => 'o',
            chr(197) . chr(142) => 'O',
            chr(197) . chr(143) => 'o',
            chr(197) . chr(144) => 'O',
            chr(197) . chr(145) => 'o',
            chr(197) . chr(146) => 'OE',
            chr(197) . chr(147) => 'oe',
            chr(197) . chr(148) => 'R',
            chr(197) . chr(149) => 'r',
            chr(197) . chr(150) => 'R',
            chr(197) . chr(151) => 'r',
            chr(197) . chr(152) => 'R',
            chr(197) . chr(153) => 'r',
            chr(197) . chr(154) => 'S',
            chr(197) . chr(155) => 's',
            chr(197) . chr(156) => 'S',
            chr(197) . chr(157) => 's',
            chr(197) . chr(158) => 'S',
            chr(197) . chr(159) => 's',
            chr(197) . chr(160) => 'S',
            chr(197) . chr(161) => 's',
            chr(197) . chr(162) => 'T',
            chr(197) . chr(163) => 't',
            chr(197) . chr(164) => 'T',
            chr(197) . chr(165) => 't',
            chr(197) . chr(166) => 'T',
            chr(197) . chr(167) => 't',
            chr(197) . chr(168) => 'U',
            chr(197) . chr(169) => 'u',
            chr(197) . chr(170) => 'U',
            chr(197) . chr(171) => 'u',
            chr(197) . chr(172) => 'U',
            chr(197) . chr(173) => 'u',
            chr(197) . chr(174) => 'U',
            chr(197) . chr(175) => 'u',
            chr(197) . chr(176) => 'U',
            chr(197) . chr(177) => 'u',
            chr(197) . chr(178) => 'U',
            chr(197) . chr(179) => 'u',
            chr(197) . chr(180) => 'W',
            chr(197) . chr(181) => 'w',
            chr(197) . chr(182) => 'Y',
            chr(197) . chr(183) => 'y',
            chr(197) . chr(184) => 'Y',
            chr(197) . chr(185) => 'Z',
            chr(197) . chr(186) => 'z',
            chr(197) . chr(187) => 'Z',
            chr(197) . chr(188) => 'z',
            chr(197) . chr(189) => 'Z',
            chr(197) . chr(190) => 'z',
            chr(197) . chr(191) => 's'
        );

        $string = strtr($string, $chars);

        return $string;
    }

    public static function download_file($archivo, $downloadfilename = null, $applicationType = null) {
        if (file_exists($archivo)) {
            $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);

            header('Content-Description: File Transfer');
            if ($applicationType != null) {
                header('Content-Type: application/' . $applicationType);
            } else {
                header('Content-Type: application/octet-stream');
            }
            header('Content-Disposition: attachment; filename=' . $downloadfilename);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archivo));

            ob_clean();
            flush();
            readfile($archivo);
            exit;
        }
    }

    public static function checkEmailList($email) {
        $validator = new \yii\validators\EmailValidator;
        return $validator->validate($email);
    }

    public static function formatToLocalDate($la_terrible_fecha) {
        if (!empty($la_terrible_fecha)) {
            $el_terrible_nuevo_formato = date("d-m-Y", strtotime($la_terrible_fecha));
            return $el_terrible_nuevo_formato;
        }
    }

    public static function formatToFullLocalDate($la_terrible_fecha) {
        if (!empty($la_terrible_fecha)) {
            $el_terrible_nuevo_formato = date("d-m-Y H:i:s", strtotime($la_terrible_fecha));
            return $el_terrible_nuevo_formato;
        }
    }

    public static function formatToDBDate($la_terrible_fecha) {
        if (!empty($la_terrible_fecha)) {
            $el_terrible_nuevo_formato = date("Y-m-d", strtotime($la_terrible_fecha));
            return $el_terrible_nuevo_formato;
        }
    }

    public static function formatToHourMinute($la_terrible_fecha) {
        if (!empty($la_terrible_fecha)) {
            $el_terrible_nuevo_formato = date("H:i", strtotime($la_terrible_fecha));
            return $el_terrible_nuevo_formato;
        }
    }

    public static function formatToFullElectronicTicket($la_terrible_fecha) {
        if (!empty($la_terrible_fecha)) {
            $el_terrible_nuevo_formato = date("Y-m-d\TH:i:s", strtotime($la_terrible_fecha));
            return $el_terrible_nuevo_formato;
        }
    }

    public static function formatRutToElectronicTicket($el_manso_rut) {
        return str_replace(".", "", $el_manso_rut);
    }

    public static function formatToFullRut($elMansoRut) {
        $rut = explode("-", $elMansoRut);
        $largo = strlen($rut[0]);
        return substr($rut[0], 0, $largo - 6) . "." . substr($rut[0], $largo - 6, 3) . "." . substr($rut[0], $largo - 3, 3) . "-" . $rut[1];
    }

    private static function getAccessToken() {
        try {
            $client = new Google\Client();
            $dirPath = getenv('GOOGLE_APPLICATION_CREDENTIALS');
            $client->setAuthConfig($dirPath);
            $client->addScope("https://www.googleapis.com/auth/firebase.messaging");
            $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
            return $accessToken;
        } catch (Exception $ex) {
            print($ex);
            return "";
        }
    }

    public static function sendNotificationPush2($title, $body, $deviceToken) {
        try {
            $url = "https://fcm.googleapis.com/v1/projects/ppam-562e6/messages:send";
            $accessToken = self::getAccessToken();
            $headers = [
                "Content-Type: application/json; UTF-8",
                "Authorization: Bearer " . $accessToken
            ];
            $notification = ["title" => $title, "body" => $body];

            $fields["message"] = array(
                'token' => $deviceToken,
                'notification' => $notification,
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                echo "FCM Send Error: " . curl_error($ch);
            }
            curl_close($ch);
        } catch (Exception $ex) {
            print($ex);
            return "FCM Send Error: " . curl_error($ch);
        }
    }
}
