<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Disponibilidad $model */
$this->title = "Modificar disponibilidad de " . $nombre_completo;
?>
<div class="disponibilidad-update">

    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= app\components\Alert::widget() ?>
            <?=
            $this->render('_form', [
                'model' => $model,
                "id" => $id,
                "turnos_x_dia" => $turnos_x_dia,
            ])
            ?>
        </div>
    </div>
</div>