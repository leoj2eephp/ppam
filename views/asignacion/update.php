<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

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
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_LARGE
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
  <button id="delete" class="btn btn-danger" data-id="<?= $model->id ?>" type="button">Eliminar</button>
</div>
<?php ActiveForm::end(); $csrfToken = Yii::$app->request->csrfToken; ?>
<form id="deleteForm" action="<?= Url::to("delete") ?>" method="POST" style="display: none;">
  <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
  <input type="hidden" name="id" id="hiddenId">
</form>
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

    const deleteButton = document.querySelector("#delete")
    deleteButton.addEventListener("click", () => {
      event.preventDefault();
      const asignacionId = event.target.getAttribute('data-id');
      Swal.fire({
        title: '¿Está seguro que quiere eliminar esta asignación?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('hiddenId').value = asignacionId;
          document.getElementById('deleteForm').submit();
        }
      });
    });
    
JS;

$this->registerJs($script);
?>