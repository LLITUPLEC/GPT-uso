<?php

use app\models\Chat;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * _@var View $this
 * _@var Chat $message
 * _@var ActiveQuery $messageQuery
 */
?>
<?= $this->render('_list', compact('messageQuery')) ?>

<div class="activity-form">
    <?php $form = ActiveForm::begin(); ?>

    <?php if (!Yii::$app->user->isGuest) echo $form->field($message, 'message')->textarea(['rows' => 5])->label('') ?>

    <div class="form-group" style="margin-top: 40px;">
        <?php if (!Yii::$app->user->isGuest) echo Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
