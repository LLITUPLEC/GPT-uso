<?php

/**
 * @var $this yii\web\View
 * @var $model Chat
 */

use app\models\Chat;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="site-about" style="margin-bottom: 30px;">
    <h1><?= Html::encode($model->id ? $model->message : 'Новое сообщение') ?></h1>

    <div class="form-group pull-right">
        <?= Html::a('Отмена', ['chat/index'], ['class' => 'btn btn-info']) ?>
    </div>
</div>

<?= $this->render('_row', [
    'model' => $model,
]) ?>
<div class="activity-form">
    <?php $form = ActiveForm::begin(); ?>

    <?php if (Yii::$app->user->can('admin')) echo $form->field($model, 'blocked')->checkbox()
        ->hint('поставьте или снимите флажок для добавления/удаления  <br> метки "некорректного сообщения" и нажмите "Сохранить"') ?>

    <div class="form-group" style="margin-top: 40px;">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
