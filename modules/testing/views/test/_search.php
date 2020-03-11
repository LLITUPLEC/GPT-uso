<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\testing\models\TestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'closed')
        ->radioList([
            '0'=>'Активные тесты',
            '1'=>'Пройденные тесты',
        ], [
            'id' => 'blog_type',
            'class' => 'form-group',
            'data-toggle' => 'radio',
            'unselect' => null,
            'item' => function ($index, $label, $name, $checked, $value) {
                return '<label class="form check-inline' . ($checked ? ' active' : '') . '">' .
                    Html::radio($name, $checked, ['value' => $value, 'class' => 'test-status-radio']) . $label . '</label>';
            },
        ]);
    ?>

    <div class="form-group" style="margin-bottom: 2em">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) . '&nbsp;&nbsp;'?>
        <?= Html::a('Создать тест', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
