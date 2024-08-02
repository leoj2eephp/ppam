<?php

use app\components\Helper;
use yii\helpers\Url;

// $this->title = 'PPAM Osorno';
?>
<div class="container-fluid">
    <?= app\components\Alert::widget() ?>
    <input type="hidden" id="csrf-token" value="<?= Yii::$app->request->getCsrfToken() ?>">
    <h2>Noticias</h2>
    <div class="row">
        <?php
        foreach ($noticias as $noti) : ?>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body-info card-animada d-flex">
                        <div class="info-icon bg-info text-white d-flex align-items-center justify-content-center mr-1">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="info-content">
                            <h5 class="card-title"><?= $noti->titulo . ' | ' . Helper::formatToLocalDate($noti->fecha) ?></h5>
                            <p class="card-text">
                                <?= $noti->contenido ?>
                            </p>
                            <!-- <a href="#" onclick='mostrarNoticia("<?= $noti->titulo ?>", <?= json_encode($noti->contenido) ?>)'>Leer más...</a> -->
                            <a href="#" onclick='mostrarNoticia(<?= json_encode($noti->titulo) ?>, <?= json_encode(nl2br($noti->contenido)) ?>)'>Leer más...</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    </div>


    <h2>Mis Asignaciones</h2>
    <div class="row">
        <?php
        if (count($asignaciones) == 0) { ?>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body-info card-animada d-flex">
                        <div class="info-icon bg-warning text-white d-flex align-items-center justify-content-center mr-1">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div class="info-content">
                            <h5 class="card-title">Sin Asignaciones</h5>
                            <p class="card-text">
                                Por el momento no ha recibido asignaciones para trabajar en PPAM Osorno.
                                Verifique su Disponibilidad <a href="/disponibilidad/update">Aquí</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else {
            foreach ($asignaciones as $asig) : ?>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body-info d-flex">
                            <div class="info-icon bg-primary text-white d-flex align-items-center justify-content-center mr-1">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="info-content">
                                <h5 class="card-title">
                                    <?= Helper::formatToLocalDate($asig->fecha) ?> |
                                    <?= $asig->punto->nombre ?>
                                    (<?= Helper::formatToHourMinute($asig->turno->desde) ?>-<?= Helper::formatToHourMinute($asig->turno->hasta) ?>)
                                </h5>
                                <p class="card-text">
                                    <i class="fas fa-user"></i> <?= $asig->user1->nombreCompleto ?>
                                    <span>
                                        | <?php
                                            $spanEstado = "";
                                            $estado = $asig->user1->id == Yii::$app->user->id ? "estado" : "";
                                            $datos = 'data-id="' . $asig->id . '" data-confirm="1"';
                                            if (!isset($asig->confirmado1)) {
                                                $spanEstado = "<span class='text-info " . $estado . " sin-confirmar text-bold' 
                                                    data-estado='sin-confirmar' " . $datos . ">Sin confirmar ";
                                            } else if ($asig->confirmado1) {
                                                $spanEstado = "<span class='text-success " . $estado . " confirmar text-bold'
                                                    data-estado='confirmado' " . $datos . ">Confirmado ";
                                            } else {
                                                $spanEstado = "<span class='text-danger " . $estado . " rechazar text-bold'
                                                    data-estado='rechazado' " . $datos . ">Rechazado ";
                                            } ?>
                                        <?php $asig->user1->id == Yii::$app->user->id ?
                                            $spanEstado .= "<i class='fas fa-edit link text-info'></i></span>" : "</span>" ?>
                                        <?= $spanEstado ?>
                                    </span>
                                </p>
                                <p>
                                    <i class="fas fa-user"></i> <?= $asig->user2->nombreCompleto ?>
                                    <span>
                                        | <?php
                                            $spanEstado = "";
                                            $estado = $asig->user2->id == Yii::$app->user->id ? "estado" : "";
                                            $datos = 'data-id="' . $asig->id . '" data-confirm="2"';
                                            if (!isset($asig->confirmado2)) {
                                                $spanEstado = "<span class='text-info " . $estado . " sin-confirmar text-bold' 
                                                data-estado='sin-confirmar' " . $datos . ">Sin confirmar ";
                                            } else if ($asig->confirmado2) {
                                                $spanEstado = "<span class='text-success " . $estado . " confirmar text-bold'
                                                data-estado='confirmado' " . $datos . ">Confirmado ";
                                            } else {
                                                $spanEstado = "<span class='text-danger " . $estado . " rechazar text-bold'
                                                data-estado='rechazado' " . $datos . ">Rechazado ";
                                            } ?>
                                        <?php $asig->user2->id == Yii::$app->user->id ?
                                            $spanEstado .= "<i class='fas fa-edit link text-info'></i></span>" : "</span>" ?>
                                        <?= $spanEstado ?>
                                    </span>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
        <?php
            endforeach;
        }
        ?>
    </div>
</div>
<meta name="base-url" content="<?= Url::to(['/asignacion/confirm-reject']) ?>">
<?php
$script = <<< JS
    const urlConfirmReject = document.querySelector('meta[name="base-url"]').getAttribute('content');
    const estados = document.querySelectorAll('.estado');

    estados.forEach(function (estadoElement) {
        estadoElement.addEventListener('click', function () {
            const estado = this.dataset.estado;
            const id = this.dataset.id;
            const confirmado = this.dataset.confirm;

            let config = {
                title: '',
                // text: '¿Deseas cambiar el estado?',
                icon: '',
                showCancelButton: true,
                confirmButtonColor: '',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '',
                cancelButtonText: 'Cancelar'
            };

            switch (estado) {
                case 'sin-confirmar':
                    config.title = 'Confirmación requerida';
                    config.html = 'Presione <span class="text-success text-bold">Aceptar</span> para confirmar su participación.<br />';
                    config.html += 'Presione <span class="text-danger text-bold">Rechazar</span> si no podrá realizarla.';
                    config.icon = 'info';
                    config.showDenyButton = true;
                    config.confirmButtonColor = '#28a745';
                    config.confirmButtonText = 'Aceptar';
                    config.denyButtonColor = '#dc3545';
                    config.denyButtonText = "Rechazar";
                    config.cancelButtonColor = 'gray';
                    config.cancelButtonText = 'Cancelar';
                    break;
                case 'confirmado':
                    config.title = 'Confirmado';
                    config.html = 'Presione <span class="text-danger text-bold">Rechazar</span> si no podrá realizar su asignación.<br />';
                    config.icon = 'info';
                    config.showConfirmButton = false;
                    config.showDenyButton = true;
                    config.showCancelButton = true;
                    config.denyButtonColor = '#dc3545';
                    config.denyButtonText = "Rechazar";
                    config.cancelButtonColor = 'gray';
                    config.cancelButtonText = 'Cancelar';
                    break;
                case 'rechazado':
                    config.title = 'Rechazado';
                    config.html = 'Presione <span class="text-success text-bold">Aceptar</span> si ahora puede realizar su asignación.<br />';
                    config.icon = 'error';
                    config.showConfirmButton = true;
                    config.showCancelButton = true;
                    config.showDenyButton = false;
                    config.confirmButtonColor = '#28a745';
                    config.confirmButtonText = 'Aceptar';
                    config.cancelButtonColor = 'gray';
                    config.cancelButtonText = "Cancelar";
                    break;
            }

            Swal.fire(config).then((result) => {
                var data = {}
                console.log(result)
                if (!result.isDismissed) {
                    if (result.isConfirmed) {
                        data = { id: id, confirm: confirmado, estado: 1 }
                    } else if (result.isDenied) {
                        data = { id: id, confirm: confirmado, estado: 0 }
                    }
                    fetchData(data, estadoElement)
                }
            });
        });
    });

    function fetchData(data, estadoElement) {
        fetch(urlConfirmReject, {
            method: "post",
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector("#csrf-token").value
            },
            body: JSON.stringify(data)
        }).then((res) => res.json())
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire({title: 'Error', text: 'Ocurrió un error al procesar la solicitud', icon: 'error'});
        })
        .then((response) => {
            if (response.success) {
                Swal.fire({
                    title: 'Éxito', text: response.message, icon: 'success'})
                .then(() => {
                    if (data.estado === 1) handleConfirm(estadoElement)
                    else if (data.estado === 0) handleCancel(estadoElement)
                })
            } else {
                Swal.fire({title: 'Error', text: response.message, icon: 'error'});
            }
        });
    }

    function handleConfirm(element) {
        const estado = element.dataset.estado;
        console.log(estado)
        if (estado === 'sin-confirmar' || estado === 'rechazado') {
            updateState(element, 'confirmado', 'text-success', 'Confirmado');
        } else if (estado === 'confirmado') {
            updateState(element, 'rechazado', 'text-danger', 'Rechazado');
        }
    }

    function handleCancel(element) {
        const estado = element.dataset.estado;
        if (estado === 'sin-confirmar' || estado === "confirmado") {
            updateState(element, 'rechazado', 'text-danger', 'Rechazado');
        }
    }

    function updateState(element, newState, newClass, newText) {
        element.classList.remove('text-info', 'text-success', 'text-danger');
        element.classList.add(newClass);
        element.dataset.estado = newState;
        element.innerHTML = newText + " <i class='fas fa-edit link '"+ newClass +"'></i>";
    }

    function mostrarNoticia(titulo, contenido) {
        Swal.fire({
            title: titulo,
            html: contenido,
            icon: 'info',
            confirmButtonText: 'Cerrar',
            customClass: {
                popup: 'custom-swal-popup',
                title: 'custom-swal-title',
                htmlContainer: 'custom-swal-html'
            }
        });
    }
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>