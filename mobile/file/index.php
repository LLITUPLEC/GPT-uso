<?php

/**
 * @var $this yii\web\View
 * @var $provider ActiveDataProvider
 * @property Category $category
 */

use app\assets\MobileAsset;
use app\models\Category;
use app\models\File;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
MobileAsset::register($this);

$columns = [
    [
        'label' => 'Название',
        'attribute' => 'title',
        'content' => function(File $model){
            return StringHelper::truncate($model->title, 7);
        },
        'headerOptions'=>['style'=>'width: 25px;']
    ],
    [
        'label' => 'Категория',
        'attribute' => 'category_id', // авто-подключение зависимостей
        'value' => function (File $model) {
            return $model->category->title;
        },
        // $model->category->title
        'headerOptions'=>['style'=>'width: 25px;']
    ],
    [
        'label' => 'Ссылка',
        'value' => function(File $model)
        {
            return Html::a('ознако- миться',Yii::$app->homeUrl. 'uploads/'. $model->path,['target' => '_blank']);
        },
        'format' => 'raw',
        'contentOptions' => ['style' => 'text-align:center; '],
        'headerOptions'=>['style'=>'width: 35px;']

        //рабочий вариант отображения уменьшенной копии изображения
//        'attribute' => 'path',
//        'format' => 'raw',
//        'value' => function (File $model) {
//            if ($model->path!='')
//                return '<img src="'.Yii::$app->homeUrl. 'uploads/'.$model->path.'" width="50px" height="auto">'; else return 'no image';
//        },
    ],
];

if (Yii::$app->user->can('admin')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Опции',
        'headerOptions'=>['style'=>'width: 35px;'],
    ];
} else if (Yii::$app->user->can('user')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Опции',
        'template' => '{view}',
        'headerOptions'=>['style'=>'width: 35px;']
    ];
}

?>

    <div class="site-about">
        <h1>Список файлов</h1>
        <p>
            <?php if (Yii::$app->user->can('admin')) {
                echo Html::a('Загрузить новый', ['/file/upload'], ['class' => 'btn btn-success']);
            } ?>
        </p>
    </div>

<?= GridView::widget([
    'dataProvider' => $provider, // $provider->getModels() [....]
    'columns' => $columns,
]) ?>