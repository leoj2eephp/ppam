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
        <?php foreach ($turnos_x_dia as $tr) { ?>
            <tr>
                <td><?= Helper::formatToHourMinute($tr["desde"]) . " - " . Helper::formatToHourMinute($tr["hasta"]) ?></td>
                <?php foreach ($tr["valores"] as $valores) { ?>
                    <td><label class="switch">
                            <input type="checkbox" id="<?= $tr["turno_id"] . "_" . $valores["keyDia"] ?>" 
                                <?= $valores["estado"] == 1 ? "checked" : "" ?>>
                            <span class="slider round"></span>
                        </label>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
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
                    if (data !== "OK") {
                        const estado = $(checkSwitch).is(":checked");
                        $(checkSwitch).prop('checked', !estado);
                    }
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