<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Asignacion $model */

$this->title = 'Crear Asignacion';
$this->params['breadcrumbs'][] = ['label' => 'Asignacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asignacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
