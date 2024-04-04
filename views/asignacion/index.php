<?php

use yii\helpers\Url;

?>
<div class="asignacion-index">
  <div class="card card-info">
    <div class="card-header with-border">
      <h3 class="card-title">Asignaciones</h3>
    </div>
    <div class="card-body">
      <?= app\components\Alert::widget() ?>
      <?php
      echo \yii2fullcalendar\yii2fullcalendar::widget(array(
        'events' => $events,
        "options" => ["id" => "calendar",],
        'clientOptions' => [
          'locale' => 'es',
          "dayClick" => new \yii\web\JsExpression('function(date, jsEvent, view) {
                        // Swal.fire("Clicked on: " + date.format() + "<br />Coordinates: " + jsEvent.pageX + "," + jsEvent.pageY + "<br />Current view: " + view.name);
                        Swal.fire({
                            title: title,
                            showCancelButton: true,
                            confirmButtonText: "Crear Turno",
                            showLoaderOnConfirm: true,
                            html: cuerpoDialogo,
                            width: "800px",
                            preConfirm: async (login) => {
                              try {
                                const url = "' . Url::to(['/asignacion/crear-turno'], true) . '";
                                const swalContent = Swal.getHtmlContainer();
                                const punto = swalContent.querySelector(".punto").value;
                                const turno = swalContent.querySelector(".turno").value;
                                const usuario1 = swalContent.querySelector(".usuario1").value;
                                const usuario2 = swalContent.querySelector(".usuario2").value;
                                const data = { fecha: date.format(), turno: turno, usuario1: usuario1, usuario2: usuario2, punto: punto };
                                const response = await fetch(url, {
                                    method: "POST",
                                    body: JSON.stringify(data)
                                });
                                if (!response.ok) {
                                  //  return Swal.showValidationMessage("${JSON.stringify(await response.json())}");
                                  return "ERROR";
                                }
                                return response.json();
                              } catch (error) {
                                Swal.showValidationMessage("Request failed: ${error}");
                              }
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                          }).then((result) => {
                            if (result.isConfirmed) {
                              if (result.value !== "ERROR") {
                                const asignacion = result.value;
                                const calendar = $("#calendar").fullCalendar("getCalendar");
                                const newEvent = {
                                  id: asignacion.id,
                                  title: asignacion.punto.nombre + " " + asignacion.turno.nombre,
                                  start: asignacion.fecha + " " + asignacion.turno.desde,
                                  end: asignacion.fecha + " " + asignacion.turno.hasta,
                                  color: asignacion.punto.color,
                                };
                                calendar.renderEvent(newEvent);
                                
                                const casada1 = asignacion.user1.apellido_casada ?? "";
                                const casada2 = asignacion.user2.apellido_casada ?? "";
                                const nombreCompleto1 = casada1 !== "" ?
                                  asignacion.user1.nombre + " " + asignacion.user1.apellido + " de " + casada1 : 
                                  asignacion.user1.nombre + " " + asignacion.user1.apellido;
                                const nombreCompleto2 = casada2 !== "" ?
                                  asignacion.user2.nombre + " " + asignacion.user2.apellido + " de " + casada2 : 
                                  asignacion.user2.nombre + " " + asignacion.user2.apellido;
                                Swal.fire({
                                  title: "Turno creado exitosamente!",
                                  icon: "success",
                                  html: "<p>Se creó el turno para el día " + asignacion.fecha + "<br />" +
                                  "Se ha asignado a los siguientes voluntarios: </p>" +
                                  "<ul><li>" + nombreCompleto1 + "</li><li>" + nombreCompleto2 + "</li>",
                                });
                              } else {
                                Swal.fire({
                                  title: "Error al crear el turno!",
                                  icon: "danger",
                                  text: result.value
                                });
                              }
                            }
                          });                          
                    }'),
                    "eventClick" => new \yii\web\JsExpression('function(calEvent, jsEvent, view) {
                      Swal.fire({
                        title: calEvent.title,
                        // icon: "danger",
                        text: "Coordinates: " + jsEvent.pageX + "," + jsEvent.pageY + ". " + "View: " + view.name
                      });
                    }'),
        ],
      ));
      ?>
    </div>
  </div>
</div>

<div id="crear_turno" style="display: none;">
  <form id="form_turno">
    <div class="row">
      <div class="col-sm-6">
        <label for="punto">Seleccione Punto</label>
        <select class="form-control form-control-lg punto">
          <option value="-1">No Seleccionado</option>
          <?php foreach ($puntos as $p) : ?>
            <option value="<?= $p->id ?>"><?= $p->nombre ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label for="turno">Seleccione Turno</label>
        <select class="form-control form-control-lg turno">
          <option value="-1">No Seleccionado</option>
          <?php foreach ($turnos as $t) : ?>
            <option value="<?= $t->id ?>"><?= $t->horario ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-sm-6">
        Participante 1
        <select class="form-control usuario1">
          <option value="-1">No Seleccionado</option>
          <?php foreach ($usuarios as $u) : ?>
            <option value="<?= $u->id ?>"><?= $u->nombreCompleto ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-sm-6">
        Participante 2
        <select class="form-control usuario2">
          <option value="-1">No Seleccionado</option>
          <?php foreach ($usuarios as $u) : ?>
            <option value="<?= $u->id ?>"><?= $u->nombreCompleto ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>
  </form>
</div>
<?php
$script = <<< JS
    const title = "Creación de Turno";
    const crear_turno = document.querySelector("#crear_turno");
    const cuerpoDialogo = crear_turno.innerHTML;
JS;

$this->registerJs($script);
?>