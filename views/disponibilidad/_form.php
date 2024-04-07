<?php

/** @var yii\web\View $this */

use app\components\Helper;
use app\models\Dias;

/** @var app\models\Disponibilidad $model */
/** @var yii\widgets\ActiveForm $form */
?>
Disponibilidad

<table class="table table-striped">
    <thead>
        <tr>
            <th>Horarios</th>
            <?php foreach (Dias::getAll() as $dia) : ?>
                <td><?= $dia ?></td>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($turnos as $turno) : ?>
            <tr>
                <td><?= Helper::formatToHourMinute($turno->desde) . " - " . Helper::formatToHourMinute($turno->hasta) ?></td>
                <td><label class="switch">
                        <input type="checkbox" id="<?= $turno->id . "_1" ?>">
                        <span class="slider round"></span>
                    </label>
                </td>
                <td><label class="switch">
                        <input type="checkbox" id="<?= $turno->id . "_2" ?>">
                        <span class="slider round"></span>
                    </label>
                </td>
                <td><label class="switch">
                        <input type="checkbox" id="<?= $turno->id . "_3" ?>">
                        <span class="slider round"></span>
                    </label>
                </td>
                <td><label class="switch">
                        <input type="checkbox" id="<?= $turno->id . "_4" ?>">
                        <span class="slider round"></span>
                    </label>
                </td>
                <td><label class="switch">
                        <input type="checkbox" id="<?= $turno->id . "_5" ?>">
                        <span class="slider round"></span>
                    </label>
                </td>
                <td><label class="switch">
                        <input type="checkbox" id="<?= $turno->id . "_6" ?>">
                        <span class="slider round"></span>
                    </label>
                </td>
                <td><label class="switch">
                        <input type="checkbox" id="<?= $turno->id . "_7" ?>">
                        <span class="slider round"></span>
                    </label>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<?php
$script = <<< JS
    $(document).ready(function() {
        $("[type=checkbox]").on("click", function() {
            const [turnoId, dia] = $(this).attr("id").split("_");
            const checkSwitch = $(this);
            var jsondata = {
                userId:  $id,
                turnoId: turnoId,
                estado: $(this).is(":checked"),
                dia: dia,
            };
            $.ajax({
                url: "update-turno-dia",
                type: "post",
                data: { json: JSON.stringify(jsondata) },
                success: function(data) {
                    console.log("Realizado");
                },
                error: function(xhr, status, error) {
                    const estado = $(checkSwitch).is(":checked");
                    $(checkSwitch).prop('checked', !estado);
                }
            });
        });
    });
JS;
$this->registerJs($script);
?>