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
                    [
                        "attribute" => "titulo",
                        "label" => "Título",
                        'format' => 'html',
                        'headerOptions' => ['class' => 'lg-column-width'],
                        'contentOptions' => ['class' => 'lg-column-width'],
                    ],
                    [
                        "attribute" => "contenido",
                        "label" => "Contenido",
                        'format' => 'html',
                        'headerOptions' => ['class' => 'xxl-column-width'],
                        'contentOptions' => ['class' => 'xxl-column-width'],
                        'content' => function ($model) {
                            return '<div class="truncated-text">' . \yii\helpers\Html::encode($model->contenido) . '</div>';
                        },
                    ],
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
                        'headerOptions' => ['class' => 'sm-column-width'],
                        'contentOptions' => ['class' => 'sm-column-width'],
                        'value' => function ($model) {
                            $switch = '<label class="switch">';
                            $isChecked = $model->estado ? "checked" : "";
                            $checkbox = "<input type='checkbox' " . $isChecked . "/><span class='slider round'></span>";
                            $switch .= $checkbox . "</label>";
                            return $switch;
                        },
                    ],
                    //'user_id',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
<?php
$script = <<< JS
    $(document).ready(function() {
        $("[type=checkbox]").on("click", function() {
            const [turnoId, dia] = $(this).attr("id").split("_");
            const checkSwitch = $(this);
            var jsondata = {
                id: id,
                estado: $(this).is(":checked"),
            };
            $.ajax({
                url: "update-estado",
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