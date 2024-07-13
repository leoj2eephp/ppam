<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Solicitar restablecimiento de contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="password-renew">
    <?= app\components\Alert::widget() ?>
    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><i class="fas fa-lock"></i> Recuperar contraseña</h3>
        </div>
        <div class="card-body">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Por favor, ingresa tu correo electrónico. Se te enviará un enlace para restablecer la contraseña.</p>

            <div class="row">
                <div class="col-lg-5">
                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>