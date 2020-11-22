<?php

/**
 * @var $this yii\web\View
 * @var $provider ActiveDataProvider
 * @property User $user
 */

use app\models\Chat;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

?>

<div class="site-about">
    <h1>Список некорректных сообщений</h1>

    <div class="form-group pull-right">
        <?= Html::a('К чату', ['chat/index'], ['class' => 'btn btn-info']) ?>
    </div>
</div>

<?php
$columns = [
    [
        'label' => 'Порядковый номер',
        'value' => function (Chat $model) {
            return "# {$model->id}";
        },
    ],
    //'user_id',
    [
        'label' => 'Кто создал',
        'attribute' => 'user_id', // авто-подключение зависимостей
        'value' => function (Chat $model) {
            return $model->user->last_name;
        }
        // $model->user->username
    ],
    'message',
    'created_at:datetime',
    'updated_at:datetime',
];

if (Yii::$app->user->can('admin')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'template' => '{update}{delete}',
        'contentOptions' => ['style' => 'text-align:center; '],
    ];
}

?>

<?= GridView::widget([
    'dataProvider' => $provider, // $provider->getModels() [....]
    'columns' => $columns,
]) ?>

