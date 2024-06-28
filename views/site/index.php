<?php

use app\components\Helper;

$this->title = 'PPAM Osorno';
?>
<!-- <img src="<?= Yii::getAlias("@web") . "/images/logo.png" ?>" alt="logo" width="1000"> -->
<div class="container-fluid">
    <div class="row">
        <?php
        foreach ($noticias as $noti) : ?>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body-info card-animada d-flex">
                        <div class="info-icon bg-info text-white d-flex align-items-center justify-content-center mr-1">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="info-content">
                            <h5 class="card-title"><?= $noti->titulo . ' | ' . Helper::formatToLocalDate($noti->fecha) ?></h5>
                            <p class="card-text">
                                <?= $noti->contenido ?>
                            </p>
                            <a href="#">Leer más...</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    </div>
</div>