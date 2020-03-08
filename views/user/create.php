<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Создание пользователя';

$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <h5 style="margin-top: -10px; color: #ff7c23">Учтите, что табельный номер тоже должен быть уникальным</h5>

    <div class="row">
        <div class="form-group pull-right">
            <?= Html::a('Назад', ['user/index'], ['class' => 'btn btn-info']) ?>
        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
