<?php
use kartik\time\TimePicker;
use kartik\widgets\SwitchInput;

/** @var yii\web\View $this */
/** @var common\models\Turno $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'desde')->textInput()->widget(TimePicker::class, [
    'pluginOptions' => [
        'showMeridian' => false, // Opcional: si deseas utilizar un formato de 24 horas
        'defaultTime' => 'current', // Opcional: si deseas establecer la hora predeterminada
    ]
])?>
<?= $form->field($model, 'hasta')->textInput()->widget(TimePicker::class, [
    'pluginOptions' => [
        'showMeridian' => false, // Opcional: si deseas utilizar un formato de 24 horas
        'defaultTime' => 'current', // Opcional: si deseas establecer la hora predeterminada
    ]
])?>
<?= $form->field($model, 'estado')->widget(SwitchInput::class, [
    'pluginOptions' => [
        'size' => 'large',
        'onColor' => 'success',
        'offColor' => 'danger',
        'value' => true,
    ]
]) ?>
<?php // $form->field($model, 'orden')->textInput() ?>