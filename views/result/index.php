<?php

/**
 * @var $this yii\web\View
 * @var $provider ActiveDataProvider
 */

use app\models\Result;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$columns = [

    [
        'label' => 'Название теста',
        'attribute' => 'test_id', // авто-подключение зависимостей
        'value' => function (Result $model) {
            return $model->test->name;
        },
        'contentOptions' => ['style' => 'text-align:center'],
        'headerOptions'=>['style' => 'text-align:center'],
        // $model->test->name
    ],
    //'user_id',
    [
        'label' => 'Кто проходил',
        'attribute' => 'user_id', // авто-подключение зависимостей
        'value' => function (Result $model) {
            return $model->user->getFullName();
        },
        'visible' => Yii::$app->user->can('admin'),
        'contentOptions' => ['style' => 'text-align:center'],
        'headerOptions'=>['style' => 'text-align:center'],
        // $model->user->last_name
    ],
    [
        'attribute' => 'date_test', 'format' => ['datetime'],
        'contentOptions' => ['style' => 'text-align:center'],
        'headerOptions'=>['style' => 'text-align:center'],
    ],
//    'date_test:datetime',
//    'attempts',
    [
        'attribute' => 'attempts',
        'contentOptions' => ['style' => 'text-align:center'],
        'headerOptions'=>['style' => 'text-align:center'],
    ],
//    'quantity',
    [
        'attribute' => 'quantity',
        'contentOptions' => ['style' => 'text-align:center'],
        'headerOptions'=>['style' => 'text-align:center'],
    ],
    [
        'attribute' => 'status',
        'format' => 'boolean',
//        'options' => ['style' => 'width: 65px; color:blue'],
        'contentOptions' => function (Result $model){
            if ($model->status == 1) {
                return ['style' => 'background-color:#1fc61fd1; font-weight:bold; text-align:center'];
            } else {
                return ['style' => 'background-color:#f31d1dd4; font-weight:bold; text-align:center'];
            }
        },
        'headerOptions'=>['style' => 'text-align:center'],
    ],
];

if (Yii::$app->user->can('admin')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'template' => '{view}',
        'contentOptions' => ['style' => 'text-align:center'],
        'headerOptions'=>['style' => 'text-align:center'],
    ];
} else if (Yii::$app->user->can('user')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'template' => '{view}',
        'contentOptions' => ['style' => 'text-align:center'],
        'headerOptions'=>['style' => 'text-align:center'],
    ];
}

?>

    <div class="row">
        <h1>Список результатов</h1>
    </div>

<?= GridView::widget([
    'dataProvider' => $provider, // $provider->getModels() [....]
    'columns' => $columns,
]) ?>