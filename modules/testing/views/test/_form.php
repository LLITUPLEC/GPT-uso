<?php

use app\models\Result;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\testing\models\Test */
/* @var $form yii\widgets\ActiveForm */

Result::getCountUsers();
?>

<div class="test-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'closed')->radioList([0 => 'Активный',1 => 'НЕактивный',]) ?>

    <?= $form->field($model, 'passed')->hiddenInput(['value' => 0])->label('')?>

    <?= $form->field($model, 'total')->hiddenInput(['value' => Result::$countUsers])->label('')?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
