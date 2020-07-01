<?php

/**
 * @var $this yii\web\View
 * @var $dataProvider ActiveDataProvider
 * @var $searchModel app\models\AcquaintSearch
 */

use app\models\Acquaint;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$columns = [
    [
        'label' => 'Номер документа',
        'attribute' => 'number', // авто-подключение зависимостей
        'value' => 'file.number',
        'contentOptions' => ['style' => 'text-align:center'],
        'headerOptions'=>['style' => 'text-align:center'],
        // $model->test->name
    ],
    //'user_id',
    [
        'label' => 'Кто ознакомился',
        'attribute' => 'user_id', // авто-подключение зависимостей
        'value' => function (Acquaint $model) {
            return $model->user->last_name;
        },
        'visible' => Yii::$app->user->can('admin'),
        'contentOptions' => ['style' => 'text-align:center'],
        'headerOptions'=>['style' => 'text-align:center'],
        // $model->user->last_name
    ],
    [
        'attribute' => 'created_at', 'format' => ['datetime'],
        'contentOptions' => ['style' => 'text-align:center'],
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
        <h1>Список ознакомившихся</h1>
    </div>

    <div class="site-about" style="margin-bottom: 20px;">
        <div class="form-group pull-right">
            <?= Html::a('К списку файлов', ['file/index'], ['class' => 'btn btn-info']) ?>
        </div>
    </div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns,
]) ?>