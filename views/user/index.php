<?php

use app\models\User;
use yii\helpers\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
// Si está loggeado como supervisor modificar las posibles acciones
$acciones_permitidas = '{disponibilidad} {update} {delete}';
if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, "supervisor")) {
    $acciones_permitidas = '{disponibilidad} {delete}';
}
?>
<div class="user-index">

    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= app\components\Alert::widget() ?>
            <p>
                <?= Html::a('Crear Usuario', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    // 'id',
                    'username',
                    // 'auth_key',
                    // 'password_hash',
                    // 'password_reset_token',
                    'nombre',
                    'apellido',
                    'apellido_casada',
                    /* [
                        'attribute' => 'genero',
                        'label' => 'Género',
                        'value' => function($model) {
                            return $model->genero == 1 ? "Masculino" : "Femenino";
                        }
                    ], */
                    'telefono',
                    'email:email',
                    [
                        'attribute' => 'ultima_sesion',
                        'format' => ['date', 'php:d/m/Y'],
                        'label' => 'Última Sesión',
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Estado',
                        'value' => function ($model) {
                            // Aquí puedes personalizar la visualización según el valor de la columna 'status'
                            switch ($model->status) {
                                case User::STATUS_ACTIVE:
                                    return 'Activo';
                                case User::STATUS_INACTIVE:
                                    return 'Inactivo';
                                case User::STATUS_DELETED:
                                    return 'Deshabilitado';
                                default:
                                    return $model->status;
                            }
                        },
                    ],
                    // 'created_at:date',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => $acciones_permitidas,
                        'buttons' => [
                            'disponibilidad' => function ($url, $model, $key) {
                                if ($model->username !== "admin") {
                                    return Html::a('<span class="fas fa-calendar"></span>', 
                                        ['disponibilidad/update', 'id' => $model->id], ["title" => "Cambiar Disponibilidad"]);
                                }
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>