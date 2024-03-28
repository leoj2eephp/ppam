<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Asignacion $model */

$this->title = 'Update Asignacion: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Asignacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="asignacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
