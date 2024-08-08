<?php

use app\models\Congregacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CongregacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Congregacions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="congregacion-index">
    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= app\components\Alert::widget() ?>
            <p>
                <?= Html::a('Crear Congregaeción', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nombre',
                    // 'numero',
                    [
                        "attribute" => "numero",
                        'format' => 'html',
                        'headerOptions' => ['class' => 'sm-column-width'],
                        'contentOptions' => ['class' => 'sm-column-width'],
                        'value' => function($model) {
                            return $model->numero ?? "";
                        }
                    ],
                    'circuito',
                    [
                        "attribute" => "ciudad_id",
                        "label" => "Ciudad",
                        'format' => 'html',
                        'headerOptions' => ['class' => 'lg-column-width'],
                        'contentOptions' => ['class' => 'lg-column-width'],
                        'value' => function($model) {
                            return $model->ciudad->nombre;
                        }
                    ],
                    [
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, Congregacion $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>