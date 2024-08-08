<?php

use app\models\AuthItem;
use app\models\Congregacion;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */

$generos = [1 => "Masculino", 2 => "Femenino"];
?>
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'apellido_casada')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'genero')->radioList($generos, ['inline' => true]) ?>
    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'rol')->widget(Select2::class, [
        'data' => ArrayHelper::map(AuthItem::getRoles(), "name", "name"),
        'options' => ['placeholder' => 'Seleccione un Rol ...'],
    ]) ?>
    <?= $form->field($model, 'congregacion_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Congregacion::find()->all(), "id", "nombre"),
        'options' => ['placeholder' => 'Seleccione Congregación ...'],
    ]) ?>
