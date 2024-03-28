<?php

/** @var yii\web\View $this */
/** @var common\models\Asignacion $model */
/** @var yii\widgets\ActiveForm $form */
?>
    <?= $form->field($model, 'fecha')->textInput() ?>
    <?= $form->field($model, 'confirmado1')->textInput() ?>
    <?= $form->field($model, 'confirmado2')->textInput() ?>
    <?= $form->field($model, 'no_realizado')->textInput() ?>
    <?= $form->field($model, 'user_id1')->textInput() ?>
    <?= $form->field($model, 'user_id2')->textInput() ?>
    <?= $form->field($model, 'turno_id')->textInput() ?>
    <?= $form->field($model, 'punto_id')->textInput() ?>