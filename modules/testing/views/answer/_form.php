<?php

use app\modules\testing\models\Question;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\testing\models\Answer */
/* @var $form yii\widgets\ActiveForm */
$id_question = Yii::$app->request->get('ID_question');
?>

<div class="answer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_question',['inputOptions' => ['value' => $id_question]])->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'type')->radioList(['right' => 'правильный' , 'wrong' => 'неправильный'], ['value' => 'wrong']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить',  [['id_test' => '$ID_test'],'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
