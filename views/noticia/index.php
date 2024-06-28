<?php

use app\components\Helper;
use app\models\Noticia;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\NoticiaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Noticias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="noticia-index">

    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= app\components\Alert::widget() ?>
            <p>
                <?= Html::a('Crear Noticia', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    // 'id',
                    'titulo',
                    'contenido:ntext',
                    [
                        "attribute" => "fecha",
                        "label" => "Fecha",
                        "value" => function ($model) {
                            return Helper::formatToFullLocalDate($model->fecha);
                        }
                    ],
                    [
                        'attribute' => 'estado',
                        'label' => 'Estado',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->estado ? "Visible" : "No Visible";
                        },
                    ],
                    //'user_id',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            /* 'turnos' => function ($url, $model, $key) {
                                // Puedes personalizar el botón de la nueva acción aquí
                                return Html::a('<span class="fas fa-clock"></span>', ['punto/update-turnos', 'id' => $model->id], ["title" => "Turnos Asociados"]);
                            }, */
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>