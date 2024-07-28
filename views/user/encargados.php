<?php

use app\models\Dias;
use app\models\User;

?>
<div class="row">
    <input type="hidden" id="csrf-token" value="<?= Yii::$app->request->getCsrfToken() ?>">
    <?php foreach ($model as $dato) : ?>
        <div class="col-md-3 mb-4">
            <div class="card card-animada">
                <div class="card-header">
                    <?= Dias::getDayName($dato->dia) ?>
                    <span class="text-white edit-icon" data-id="<?= $dato->dia ?>">
                        <i class="fas fa-edit"></i>
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="card-title" id="nombre_<?= $dato->dia ?>"><?= $dato->user->nombreCompleto ?? "Sin Encargado" ?></h5>
                    <p class="card-text" id="telefono_<?= $dato->dia ?>">Teléfono: <?= $dato->user->telefono ?? "Sin Número" ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <select class="encargados form-control" style="display: none;">
        <?php foreach ($encargados as $e) : ?>
            <option value="<?= $e->id ?>"><?= $e->nombreCompleto ?></option>
        <?php endforeach; ?>
    </select>
</div>
<?php
$script = <<< JS
    const encargados = document.querySelector(".encargados")
    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.addEventListener('click', async (event) => {
            event.preventDefault();
            const dia = event.currentTarget.dataset.id;
            encargados.style.display = 'block'

            const { value: datos } = await Swal.fire({
                title: 'Editar información',
                html: encargados,
                focusConfirm: false,
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Actualizar',
                preConfirm: () => {
                    const datos = { dia: dia, encargado: encargados.value }
                    return datos
                }
            });

            if (datos) {
                var url = "<?= Url::to(['/user/update-encargado-dia']) ?>"
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': document.querySelector("#csrf-token").value
                        },
                        body: JSON.stringify(datos)
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const result = await response.json();
                    if (result.status === 'ok') {
                        document.getElementById("nombre_" + datos.dia).innerText = result.user.nombre + " " + result.user.apellido
                        document.getElementById("telefono_" + datos.dia).innerText = result.user.telefono
                        Swal.fire('Actualizado', 'La información ha sido actualizada.', 'success');
                    } else {
                        Swal.fire('Error', result.message || 'Hubo un problema al actualizar.', 'error');
                    }
                } catch (error) {
                    console.log(error);
                    Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                }
            }
        });
    });
JS;

$this->registerJs($script);
?>