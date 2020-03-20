<?php

/**
 * @var $this yii\web\View
 * @var $provider ActiveDataProvider
 * @property User $user
 * @property Position $position
 */

use app\models\Briefing;
use app\models\Position;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;

$columns = [
//    [
//        'label' => 'Идентификатор',
//        'value' => function (Briefing $model) {
//            return "№{$model->id}";
//        },
//        'headerOptions'=>['style'=>'width: 30px;'],
//    ],
    [
        'class' => SerialColumn::class,
        'header' => 'Порядковый номер',
    ],
    //'id',
    'section',
    [
        'label' => 'Название',
        'attribute' => 'title',
    ],
    'date_start:date',
    [
        'label' => 'Кому назначается',
        'attribute' => 'user_id', // авто-подключение зависимостей
        'value' => function (Briefing $model) {
            return $model->user->last_name;
        }
    ],
    [
        'label' => 'Диапазон должностей',
        'attribute' => 'user_id', // авто-подключение зависимостей
        'value' => function (Briefing $model) {
            return $model->position->title;
        }
    ],
//    'repeat:boolean', // Yii::$app->formatter->asBoolean(...)
//    'blocked:boolean',
//    'updated_at:datetime',
];

if (Yii::$app->user->can('admin')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
    ];
} else if (Yii::$app->user->can('user')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'template' => '{view}'
    ];
}

?>

    <div class="site-about" style="margin-top: -50px;margin-bottom: 10px;">
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



