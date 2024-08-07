<?php

use yii\helpers\Html;
?>
<?= app\components\Alert::widget() ?>
<div class="row justify-content-center">
    <div class="card col-md-6" style="padding: 0; border-radius: 10px !important;">
        <div class="card-header-login text-center">
            <img src="<?= Yii::getAlias("@web") . "/images/logo.png" ?>" alt="Logo" class="img-fluid" style="max-height: 150px;">
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Para comenzar, inicie sesión</p>

            <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']) ?>

            <?= $form->field($model, 'username', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
                'template' => '{beginWrapper}{input}{error}{endWrapper}',
                'wrapperOptions' => ['class' => 'input-group mb-3']
            ])
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

            <?= $form->field($model, 'password', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
                'template' => '{beginWrapper}{input}{error}{endWrapper}',
                'wrapperOptions' => ['class' => 'input-group mb-3']
            ])
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

            <div class="row">
                <div class="col-12">
                    <?= Html::submitButton('Iniciar Sesión', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>

            <?php \yii\bootstrap4\ActiveForm::end(); ?>

            <!-- Enlace de Recuperación de Contraseña -->
            <div class="text-center">
                <p>
                    <?= Html::a('¿Olvidaste tu contraseña?', ['site/request-password-reset']) ?>
                </p>
            </div>
        </div>
    </div>
</div>