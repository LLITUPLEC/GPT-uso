<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $model app\models\Comment
 * @var $form ActiveForm
 */

?>

<div class="activity-form">
    <h3>Добавить комментарий</h3>

    <?php Pjax::begin(['id' => 'new-comment']) ?>
    <?php $form = ActiveForm::begin([
        'id' => 'comment-form',
        'options' => [
            'class' => 'form-horizontal',
            'data-pjax' => true,
        ],
        'fieldConfig' => [
            'template' => '<div class="col-md-1">{label}</div><div class="col-md-5">{input}</div><div class="col-md-6">{error}</div>',
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'comment')->textarea(['rows' => 5]) ?>

    <div class="form-group" style="margin-top: 40px;">
        <?= Html::submitButton('Опубликовать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end() ?>
</div>
<?php $js = <<<JS
    $("document").ready(function(){
    $("#new_comment").on("pjax:end", function() {
    $.pjax.reload({container:"#grid-pjax"},{timeout : false});  //Reload GridView
        });
    });
JS;

$this->registerJs($js);