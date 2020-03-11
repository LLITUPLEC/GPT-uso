<?php

/**
 * @var $this yii\web\View
 * @var $provider ActiveDataProvider
 * @property User $user
 * @property Position $position
 */

use app\assets\MobileAsset;
use app\models\Briefing;
use app\models\Position;
use app\models\User;
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
        'content' => function(Briefing $model){
            return StringHelper::truncate($model->title, 9);
        },
        'headerOptions'=>['style'=>'width: 25px;'],
        'options' => ['style' => 'background-color:rgba(128, 203, 255, 0.64);'],
    ],
    [
        'label' => 'Дата проведения',
        'attribute' => 'date_start', 'format' => ['date'],
        'headerOptions'=>['style'=>'width: 25px;'],
        'options' => ['style' => 'background-color:rgba(128, 203, 255, 0.64);'],
    ],
];

if (Yii::$app->user->can('admin')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'headerOptions'=>['style'=>'width: 45px;'],
        'options' => ['style' => 'background-color:rgba(128, 203, 255, 0.64);'],
        'contentOptions' => ['style' => 'text-align:center; '],
    ];
} else if (Yii::$app->user->can('user')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'template' => '{view}',
        'headerOptions'=>['style'=>'width: 45px;'],
        'options' => ['style' => 'background-color:rgba(128, 203, 255, 0.64);'],
        'contentOptions' => ['style' => 'text-align:center; '],
    ];
}

?>

    <div class="site-about" style="margin-top: -50px">
        <h1>Список проверок</h1>
        <?= Html::a('Назад', ['briefing/index'], ['class' => 'btn btn-info']) ?>
        <?=
        Yii::$app->user->can('admin') ? (
        Html::a('Добавить', ['briefing/update'], ['class' => 'btn btn-success pull-right'])
        ) : (
        Html::tag('p', Html::encode('Для просмотра подробного описания нажмите иконку в виде глаза'))
        )
        ?>
    </div>
<div class="card-body">
    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => $columns,
    ]) ?>
</div>