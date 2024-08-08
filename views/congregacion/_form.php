<?php

use app\models\Ciudad;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Congregacion $model */
/** @var kartik\form\ActiveForm $form */
?>

<?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'numero')->textInput() ?>
<?= $form->field($model, 'circuito')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'ciudad_id')->widget(Select2::class, [
    'data' => ArrayHelper::map(Ciudad::find()->all(), "id", "nombre"),
    'options' => ['placeholder' => 'Seleccione ciudad ...', "class" => "ciudad"],
]) ?>