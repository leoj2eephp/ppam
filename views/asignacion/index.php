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
      <input type="hidden" id="diaSemana">
      <input type="hidden" id="fechaSelected">
      <?php
      echo \yii2fullcalendar\yii2fullcalendar::widget(array(
        'events' => $events,
        "options" => ["id" => "calendar",],
        'clientOptions' => [
          'locale' => 'es',
          'eventRender' => new \yii\web\JsExpression("
            function(event, element) {
                element.find('.fc-title').html(event.title + ' ' + event.start);
                element.find('.fc-content').html(event.description);
                element.attr('data-extra-info', event.customAttribute);

                element.tooltip({
                    html: true,
                    title: event.customAttribute,
                    container: 'body',
                    placement: 'top'
                });
            }
          "),
          "dayClick" => new \yii\web\JsExpression('function(date, jsEvent, view) {
                        document.querySelector("#diaSemana").value = date.day() == 0 ? 7 : date.day()
                        document.querySelector("#fechaSelected").value = date.format()
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
                                if (usuario1 == usuario2) {
                                  swalContent.querySelector("#errorUsers").style.display = "block"
                                  return false
                                } else {
                                  swalContent.querySelector("#errorUsers").style.display = "none"
                                  const data = { fecha: date.format(), turno: turno, usuario1: usuario1, usuario2: usuario2, punto: punto };
                                  const response = await fetch(url, {
                                      method: "POST",
                                      body: JSON.stringify(data)
                                  });
                                  if (!response.ok) {
                                    //  return Swal.showValidationMessage("${JSON.stringify(await response.json())}");
                                    return "ERROR";
                                  }
                                  return await response.json();
                                }
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

                                const casada1 = asignacion.user1.apellido_casada ?? "";
                                const casada2 = asignacion.user2.apellido_casada ?? "";
                                const nombreCompleto1 = casada1 !== "" ?
                                  asignacion.user1.nombre + " " + asignacion.user1.apellido + " de " + casada1 : 
                                  asignacion.user1.nombre + " " + asignacion.user1.apellido;
                                const nombreCompleto2 = casada2 !== "" ?
                                  asignacion.user2.nombre + " " + asignacion.user2.apellido + " de " + casada2 : 
                                  asignacion.user2.nombre + " " + asignacion.user2.apellido;

                                const newEvent = {
                                  id: asignacion.id,
                                  title: asignacion.punto.nombre + " " + asignacion.turno.nombre + "\n - " + nombreCompleto1 + "\n - " + nombreCompleto2,
                                  start: asignacion.fecha + " " + asignacion.turno.desde,
                                  end: asignacion.fecha + " " + asignacion.turno.hasta,
                                  color: asignacion.punto.color,
                                };
                                calendar.renderEvent(newEvent);
                                
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
                                  icon: "error",
                                  text: result.value
                                });
                              }
                            }
                          });                          
                    }'),
          "eventClick" => new \yii\web\JsExpression('function(calEvent, jsEvent, view) {
            // console.log(calEvent.customAttribute)
            // text: "Coordinates: " + jsEvent.pageX + "," + jsEvent.pageY + ". " + "View: " + view.name
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
        <select class="form-control form-control-lg punto" id="punto">
          <option value="-1">No Seleccionado</option>
          <?php foreach ($puntos as $p) : ?>
            <option value="<?= $p->id ?>"><?= $p->nombre ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label for="turno">Seleccione Turno</label>
        <select class="form-control form-control-lg turno" id="turno">
          <option value="-1">No Seleccionado</option>
        </select>
      </div>
    </div>
    <div class="row mt-3 mb-5">
      <div class="col-sm-6">
        Participante 1
        <select class="form-control form-control-lg usuario1 usuarios">
          <option value="-1">No Seleccionado</option>
        </select>
      </div>
      <div class="col-sm-6">
        Participante 2
        <select class="form-control form-control-lg usuario2 usuarios">
          <option value="-1">No Seleccionado</option>
        </select>
      </div>
      <div class="col-sm-12">
        <label id="errorUsers" style="display: none;" class="text-danger mt-2">
          Seleccione diferentes participantes
        </label>
      </div>
    </div>
  </form>
</div>
<meta name="base-url" content="<?= Url::to(['/v1/turno/get-by-punto']) ?>">
<meta name="base-url-turn" content="<?= Url::to(['/v1/disponibilidad/by-turn-day']) ?>">
<?php
$script = <<< JS
    const title = "Creación de Turno"
    const crear_turno = document.querySelector("#crear_turno")
    const cuerpoDialogo = crear_turno.innerHTML
    
    document.addEventListener("change", function(e) {
      const punto = e.target.closest("#punto")
      const turno = e.target.closest("#turno")
      const fechaSelected = document.querySelector("#fechaSelected").value
      if (punto) {
        // Genera la URL en PHP y almacénala en una variable JavaScript
        const url = document.querySelector('meta[name="base-url"]').getAttribute('content');
        const turno = e.target.parentElement.parentElement.querySelector("#turno")
        var data = { punto_id: punto.value, dia: document.querySelector("#diaSemana").value, fecha: fechaSelected }

        fetch(url, {
          method: "POST",
          body: JSON.stringify(data),
          headers: {
            "Content-Type": "application/json",
          },
        })
        .then((res) => res.json())
        .catch((error) => console.error("Error:", error))
        .then((response) => {
          turno.innerHTML = "<option value='-1'>No Seleccionado</option>"
          response.forEach((tp, index) => {
            const option = document.createElement("option")
            option.textContent = tp.turno.nombre + " (" + tp.turno.desde + " - " + tp.turno.hasta + ")"
            option.value = tp.turno.id
            turno.appendChild(option)
          })
        });
      } else if (turno) {
        const selectUsuarios = e.target.parentNode.parentNode.parentNode.querySelectorAll(".usuarios")
        const url = document.querySelector('meta[name="base-url-turn"]').getAttribute('content');
        // var url = "/v1/disponibilidad/by-turn-day";
        var data = { turno_id: turno.value, dia: document.querySelector("#diaSemana").value }

        fetch(url, {
          method: "POST",
          body: JSON.stringify(data),
          headers: {
            "Content-Type": "application/json",
          },
        })
        .then((res) => res.json())
        .catch((error) => console.error("Error:", error))
        .then((response) => {
          selectUsuarios.forEach((usuario, i) => {
            usuario.innerHTML = "<option value='-1'>No Seleccionado</option>"
          })
          response.forEach((tp, index) => {
            const option = document.createElement("option")
            if (tp.user.apellido_casada !== null && tp.user.apellido_casada !== "")
              option.textContent = tp.user.nombre + " " + tp.user.apellido + " " + tp.user.apellido_casada
            else
              option.textContent = tp.user.nombre + " " + tp.user.apellido
            option.value = tp.user.id
            
            selectUsuarios.forEach((usuario, i) => {
              usuario.appendChild(option.cloneNode(true))
            })
          })
        });
      }
    })

JS;

$this->registerJs($script);
?>