<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Noticia $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'contenido')->textarea(['rows' => 6]) ?>
<label class="col-form-label has-star col-lg-3">Publicar</label>
<label class="switch">
    <input type="checkbox" name="Noticia[estado]" <?= $model->estado ? "checked" : "" ?>>
    <span class="slider round"></span>
</label>