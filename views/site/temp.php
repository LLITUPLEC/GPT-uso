<?php

use yii\helpers\Html; ?>

<div class="row">
<!--        <h1>Данный раздел в разработке</h1>-->
    <div class="form-group pull-left">
        <?= Html::a('На главную', ['site/index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Перейти к объявлениям', ['activity/index'], ['class' => 'btn btn-info']) ?>
    </div>
</div>

<!--<div style="background: url('/uploads/Снимок1.JPG') no-repeat; height: 680px;margin-left: 300px;"></div>-->

<div class="form-group pull-left" style="margin-left: 500px;margin-bottom: 100px;">
    <?= Html::a('Перейти к тестированию', ['test/display-test'], ['class' => 'btn btn-success']) ?>
</div>