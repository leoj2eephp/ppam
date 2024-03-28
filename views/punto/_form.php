<?php

use kartik\color\ColorInput;

/** @var yii\web\View $this */
/** @var common\models\Punto $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'latitud')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'longitud')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'color')->widget(ColorInput::class, [
        'name' => 'color_13',
        'value' => '#a814fe',
        'useNative' => true,
        'width' => '75%',
        'options' => ['placeholder' => 'Choose your color ...', 'class'=>'text-center'],
]);