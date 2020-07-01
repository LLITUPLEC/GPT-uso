<?php

/**
 * @var $this yii\web\View
 * @var $model File
 * @var $item1 Acquaint
 * @var $form ActiveForm
 */

use app\assets\MobileAsset;
use app\models\Acquaint;
use app\models\File;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
MobileAsset::register($this);
File::markDocRead($model->id);
?>
    <div class="site-about">
        <h1>Просмотр файла</h1>

        <div class="form-group pull-right">
            <?= Html::a('К списку', ['file/index'], ['class' => 'btn btn-info']) ?>
            <?php if (Yii::$app->user->can('admin')) {
                echo Html::a('Изменить', ['file/update', 'id' => $model->id], ['class' => 'btn btn-success']);
            } ?>
        </div>
    </div>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => 'Порядковый номер',
            'attribute' => 'id',
        ],
        'title',
        'date_in:date',
        'number',
        [
            'label' => 'Категория документа',
            'attribute' => 'category_id', // авто-подключение зависимостей
            'value' => function (File $model) {
                return $model->category->title;
            }
            // $model->category->title
        ],
        'created_at:datetime',
        'updated_at:datetime',

        [
            'label' => 'Ссылка на документ',
            'value' => function(File $model)
            {
                return Html::a('Ознакомиться',Yii::$app->homeUrl. 'uploads/'. $model->path,['target' => '_blank']);
            },
            'format' => 'raw',
//            'contentOptions' => ['style' => 'text-align:center; '],

            //рабочий вариант отображения уменьшенной копии изображения
//        'attribute' => 'path',
//        'format' => 'raw',
//        'value' => function (File $model) {
//            if ($model->path!='')
//                return '<img src="'.Yii::$app->homeUrl. 'uploads/'.$model->path.'" width="50px" height="auto">'; else return 'no image';
//        },
        ],
        [
            //рабочий вариант отображения уменьшенной копии изображения
        'label' => 'Содержание',
        'attribute' => 'path',
        'format' => 'raw',
        'value' => function (File $model) {
            if ($model->path!='')
                return '<img src="'.Yii::$app->homeUrl. 'uploads/'.$model->path.'"width="222px" height="auto">'; else return 'no image';
        },
        ],
    ],
]); ?>

<?php $form = ActiveForm::begin(); ?>

<?php if (Yii::$app->user->can('admin') || Acquaint::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['file_id' => $model->id])->exists()) {
    echo false;
} else {
    echo $form->field($item1, 'file_id')->hiddenInput([$model->id])->hint('Нажимая на кнопку <b>"Ознаколмен"</b>, Вы подтверждаете, что ознакомились с документом ');
    echo Html::submitButton('Ознакомлен', ['class' => 'btn btn-success']);
}
?>

<?php ActiveForm::end(); ?>
