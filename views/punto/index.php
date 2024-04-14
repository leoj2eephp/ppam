<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PuntoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Puntos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="punto-index">

    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= app\components\Alert::widget() ?>
            <p>
                <?= Html::a('Crear Punto', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'nombre',
                    'latitud',
                    'longitud',
                    'color',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{turnos} {update} {delete}',
                        'buttons' => [
                            'turnos' => function ($url, $model, $key) {
                                // Puedes personalizar el botón de la nueva acción aquí
                                return Html::a('<span class="fas fa-clock"></span>', ['punto/update-turnos', 'id' => $model->id], ["title" => "Turnos Asociados"]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>