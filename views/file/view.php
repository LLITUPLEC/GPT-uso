<?php

/**
 * @var $this yii\web\View
 * @var $model File
 */

use app\models\File;
use yii\helpers\Html;
use yii\widgets\DetailView;

File::markDocRead($model->id);
?>
    <div class="row">
        <h1>Просмотр файла</h1>

        <div class="form-group pull-right">
            <?= Html::a('К списку', ['file/index'], ['class' => 'btn btn-info']) ?>
            <?= Html::a('Изменить', ['file/update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
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