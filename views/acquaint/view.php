<?php

/**
 * @var $this yii\web\View
 * @var $model Acquaint
 */

use app\models\Acquaint;
use yii\helpers\Html;
use yii\widgets\DetailView;

?>
    <div class="site-about">
        <h1>Просмотр результата</h1>

        <div class="form-group pull-right">
            <?= Html::a('К списку', ['acquaint/index'], ['class' => 'btn btn-info']) ?>
        </div>
    </div>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            // activity.id - пример перезаписи названия столбца
            'label' => 'Порядковый номер',
            'attribute' => 'id',
            'visible' => Yii::$app->user->can('admin'),
        ],
        [
            'label' => 'Название документа',
            'attribute' => 'file_id', // авто-подключение зависимостей
            'value' => function (Acquaint $model) {
                return $model->file->title;
            }
            // $model->test->name
        ],
        [
            'label' => 'Номер документа',
            'attribute' => 'file_id', // авто-подключение зависимостей
            'value' => function (Acquaint $model) {
                return $model->file->number;
            }
            // $model->test->name
        ],
        [
            'label' => 'Кто ознакомился',
            'attribute' => 'user_id', // авто-подключение зависимостей
            'value' => function (Acquaint $model) {
                return $model->user->getFullName();
            },
            // $model->test->name
            'visible' => Yii::$app->user->can('admin'),
        ],
        [
            'attribute' => 'created_at', 'format' => ['datetime'],
            'headerOptions'=>['style' => 'text-align:center'],
        ],
        //'id',
    ],
]); ?>