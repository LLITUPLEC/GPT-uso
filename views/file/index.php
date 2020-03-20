<?php

/**
 * @var $this yii\web\View
 * @var $provider ActiveDataProvider
 * @property Category $category
 */

use app\models\Category;
use app\models\File;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$columns = [

    [
        // activity.id - пример перезаписи значения
        'label' => 'Порядковый номер',
        'value' => function (File $model) {
            return "№ {$model->id}";
        },
    ],
//    'id',
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
//    'created_at:datetime',
//    'updated_at:datetime',
    [
        'label' => 'Ссылка на документ',
        'value' => function (File $model) {
            return Html::a('Ознакомиться', Yii::$app->homeUrl . 'uploads/' . $model->path, ['target' => '_blank']);
        },
        'format' => 'raw',
        'contentOptions' => ['style' => 'text-align:center; '],

        //рабочий вариант отображения уменьшенной копии изображения
//        'attribute' => 'path',
//        'format' => 'raw',
//        'value' => function (File $model) {
//            if ($model->path!='')
//                return '<img src="'.Yii::$app->homeUrl. 'uploads/'.$model->path.'" width="50px" height="auto">'; else return 'no image';
//        },
    ],
//    [
//        'label' => 'Список ознакомившихся',
//        'value' => function (File $model) {
//            return Html::a('Посмотреть', ['/acquaint/index'] , ['target' => '_blank']);
//        },
//        'format' => 'raw',
//        'contentOptions' => ['style' => 'text-align:center; '],
//    ],
];

if (Yii::$app->user->can('admin')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'contentOptions' => ['style' => 'text-align:center; '],
    ];
} else if (Yii::$app->user->can('user')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'template' => '{view}',
        'contentOptions' => ['style' => 'text-align:center; '],
    ];
}

?>

    <div class="site-about" style="margin-bottom: 60px;">
        <h1>Список файлов</h1>
        <div class="form-group pull-left">
            <?php if (Yii::$app->user->can('admin')) {
                echo Html::a('Загрузить новый файл', ['/file/upload'], ['class' => 'btn btn-success']);
            } ?>
        </div>
        <div class="form-group pull-right">
            <?php if (Yii::$app->user->can('admin')) {
                echo Html::a('Список ознакомившихся', ['/acquaint/index'], ['class' => 'btn btn-info']);
            } ?>
        </div>
    </div>

<?= GridView::widget([
    'dataProvider' => $provider, // $provider->getModels() [....]
    'columns' => $columns,
]) ?>