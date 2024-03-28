<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\AsignacionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="asignacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'confirmado1') ?>

    <?= $form->field($model, 'confirmado2') ?>

    <?= $form->field($model, 'no_realizado') ?>

    <?php // echo $form->field($model, 'user_id1') ?>

    <?php // echo $form->field($model, 'user_id2') ?>

    <?php // echo $form->field($model, 'turno_id') ?>

    <?php // echo $form->field($model, 'punto_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
