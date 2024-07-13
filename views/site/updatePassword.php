<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Restablecer contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sitio-restablecer-contraseña">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor, elige tu nueva contraseña:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>