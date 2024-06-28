<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Noticia $model */

$this->title = 'Update Noticia: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Noticias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="noticia-update">

<div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <?php
        $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => [
                'labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_LARGE
            ],
            'fieldConfig' => [
                'options' => [
                    'class' => 'col-sm-11 form-group',
                    'tag' => 'div'
                ]
            ],
        ]);
        ?>
        <div class="card-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'form' => $form,
            ])
            ?>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
