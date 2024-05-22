<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\FileHelper;
use yii\web\Response;

class ListImagesController extends Controller {
    public function actionIndex() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $imagesPath = Yii::getAlias('@webroot/images');
        $imagesUrl = Yii::getAlias('@web/images');
        $files = FileHelper::findFiles($imagesPath, ['only' => ['*.svg', '*.png', '*.jpg', '*.jpeg', '*.gif']]);

        $images = [];
        foreach ($files as $file) {
            $images[] = $imagesUrl . '/' . basename($file);
        }

        return $images;
    }
}
