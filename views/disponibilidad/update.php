<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Disponibilidad $model */
$this->title = "Modificar disponibilidad de " . $user->nombre . " " . $user->apellido;
// $this->params['breadcrumbs'][] = ['label' => 'Disponibilidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                "user" => $user,
                "turnos_x_dia" => $turnos_x_dia,
            ])
            ?>
        </div>
    </div>
</div>