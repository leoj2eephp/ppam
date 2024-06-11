<?php

use app\models\Dias;
use app\models\Disponibilidad;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DisponibilidadSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Disponibilidads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disponibilidad-index">

    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= app\components\Alert::widget() ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'turno.nombre',
                    [
                        'attribute' => 'nombreCompleto',
                        'value' => "user.nombreCompleto",
                    ],
                    [
                        'attribute' => 'dia',
                        'label' => 'Día',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Dias::getDayName($model->dia);
                        },
                    ],
                    [
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, Disponibilidad $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>