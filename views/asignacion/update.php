<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Asignacion $model */
$this->title = "Actualizar Asignación " . $model->id;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disponibilidad-update">

  <div class="card card-info">
    <div class="card-header with-border">
      <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <?php
    $form = ActiveForm::begin([
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
      <div class="row">
        <div class="col-sm-6">
          <?= $form->field($model, 'punto_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($puntos, "id", "nombre"),
            'options' => ['placeholder' => 'Seleccione un Punto ...'],
          ]) ?>
        </div>
        <div class="col-sm-6">
          <?= $form->field($model, 'turno_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($turnos, "id", "nombre"),
            'options' => ['placeholder' => 'Seleccione un Turno ...'],
          ]) ?>
        </div>
      </div>
      <div class="row mt-3 mb-5">
        <div class="col-sm-6">
          <?= $form->field($model, 'user_id1')->widget(Select2::class, [
            'data' => ArrayHelper::map($usuariosD, "id", "nombreCompleto"),
            'options' => ['placeholder' => 'Seleccione un voluntario ...', "class" => "voluntario"],
          ]) ?>
        </div>

        <div class="col-sm-6">
          <?= $form->field($model, 'user_id2')->widget(Select2::class, [
            'data' => ArrayHelper::map($usuariosD, "id", "nombreCompleto"),
            'options' => ['placeholder' => 'Seleccione un voluntario ...', "class" => "voluntario"],
          ]) ?>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <label id="errorUsers" style="display: none;" class="text-danger mt-2">
        Seleccione diferentes participantes
      </label>
    </div>
  </div>
</div>
<div class="card-footer">
  <?= Html::a('Volver', ["/asignacion/index"], ['class' => 'btn btn-info']) ?>
  <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>
<?php
$script = <<< JS
    const noDisponibles = $usuariosND
    const asignado1 = $model->user_id1
    const asignado2 = $model->user_id2

    function agregarUsuariosNoDisponibles() {
      const voluntarios = document.querySelectorAll(".voluntario")
      voluntarios.forEach((voluntario, index) => {
        const separator = document.createElement("option")
        separator.textContent = "-- No Disponibles para este día/hora --"
        separator.disabled = true
        voluntario.appendChild(separator)
        noDisponibles.forEach((usuario, index2) => {
          const option = document.createElement("option")
          option.value = usuario.id
          if (index == 0) {
            option.selected = usuario.id == asignado1
          } else {
            option.selected = usuario.id == asignado2
          }
          option.textContent = usuario.nombre + " " + usuario.apellido
          option.classList.add("text-warning")
          voluntario.appendChild(option)
        })
      })
    }

    agregarUsuariosNoDisponibles()
    
JS;

$this->registerJs($script);
?>