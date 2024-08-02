<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'Mis Datos de Usuario';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Actualizar Mis Datos';
?>
<div class="user-update">

    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <?php
        $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => [
                'labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_LARGE
            ],
            'fieldConfig' => [
                'options' => [
                    'class' => 'col-sm-11 form-group',
                    'tag' => 'div'
                ]
            ],
        ]);
        ?>
        <div class="card-body">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'apellido_casada')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>

</div>