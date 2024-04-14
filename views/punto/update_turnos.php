<?php

use app\models\Dias;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Punto $model */

$this->title = 'Actualizar Punto: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Puntos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="punto-update">
    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <label for="turno" class="form-label">Seleccione Día</label>
                    <select class="form-control" id="dia">
                        <option value="-1">NO SELECCIONADO</option>
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
                        <option value="6">Sábado</option>
                        <option value="7">Domingo</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="turno" class="form-label">Horario</label>
                    <select class="form-control" id="turno">
                        <option value="-1">NO SELECCIONADO</option>
                        <?php foreach ($turnos as $t) : ?>
                            <option value="<?= $t->id ?>"><?= $t->nombre . " (" . $t->desde . " - " . $t->hasta . ")" ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <form action="/punto/sync-all-turns" method="post">
                <button type="button" class="btn btn-success" id="asociarTurno">Asociar Turno <i class="fa fa-plus-circle"></i></button>
                <button class="btn bg-purple" id="asociarTurno">Asociar Todos los horarios <i class="fa fa-sync"></i></button>
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <input type="hidden" name="puntoId" value="<?= $model->id ?>">
            </form>
        </div>
    </div>

    <table class="table table-striped" id="table-turno-puntos">
        <thead class="bg-purple">
            <tr>
                <th>Nombre</th>
                <th>Día</th>
                <th>Desde</th>
                <th>Hasta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($turnosPunto as $tp) : ?>
                <tr>
                    <td><?= $tp->turno->nombre ?></td>
                    <td><?= Dias::getAll()[$tp->dia] ?></td>
                    <td><?= $tp->turno->desde ?></td>
                    <td><?= $tp->turno->hasta ?></td>
                    <td>
                        <a href="<?= Url::to(["/punto/delete-turno-punto", "pId" => $tp->punto_id, "tId" => $tp->turno_id, "dia" => $tp->dia]) ?>">
                            <i class="fa fa-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$script = <<< JS
    const asociarTurno = document.querySelector("#asociarTurno");
    const turno = document.querySelector("#turno");
    const dia = document.querySelector("#dia");
    const puntoId = $model->id;
    const tabla = document.querySelector("#table-turno-puntos");
    asociarTurno.addEventListener("click", function(e) {
        if (puntoId != -1 && turno.value != -1) {

            Swal.fire({
                title: "Procesando...",
                text: "Por favor, espera un momento.",
                allowOutsideClick: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            var url = "/punto/update-turnos?id=" + $model->id;
            var data = { punto_id: puntoId, turno_id: turno.value, dia: dia.value };

            fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json",
            },
            })
            .then((res) => res.json())
            .catch((error) => console.error("Error:", error))
            .then(response => {
                Swal.close();
                if (response.status !== "ERROR") {
                    const tr = document.createElement("tr")
                    const tbody = tabla.querySelector("tbody");
                    tbody.appendChild(tr)
                    const td1 = document.createElement("td")
                    td1.textContent = response.data.nombre
                    const td2 = document.createElement("td")
                    td2.textContent = dia.options[dia.selectedIndex].text
                    const td3 = document.createElement("td")
                    td3.textContent = response.data.desde
                    const td4 = document.createElement("td")
                    td4.textContent = response.data.hasta
                    tr.appendChild(td1)
                    tr.appendChild(td2)
                    tr.appendChild(td3)
                    tr.appendChild(td4)
                } else {
                    Swal.fire({
                        title: "Acción no completada!",
                        text: "Ya está asociado este punto con el turno/horario seleccionado.",
                        icon: "error"
                    });
                }
            });
        }
    })

JS;

$this->registerJs($script);
?>