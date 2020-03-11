<?php

/**
 * @var $this yii\web\View
 * @var $model Briefing
 */

use app\assets\MobileAsset;
use app\models\Briefing;
use app\models\Position;
use yii\helpers\Html;
use yii\widgets\DetailView;
MobileAsset::register($this);

?>
    <div class="site-about">
        <h1>Просмотр события</h1>

        <div class="form-group pull-right">
            <?= Html::a('К списку', (Yii::$app->request->referrer), ['class' => 'btn btn-info']) ?>
            <?php if (Yii::$app->user->can('admin')) {
                echo Html::a('Изменить', ['briefing/update', 'id' => $model->id], ['class' => 'btn btn-success']);
            } ?>
        </div>
    </div>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            // activity.id - пример перезаписи названия столбца
            'label' => 'Порядковый номер',
            'attribute' => 'id',
        ],
        'title',
        'section',
        [
            'label' => 'Кому назначается',
            'attribute' => 'user_id', // авто-подключение зависимостей
            'value' => function (Briefing $model) {
                return $model->user->getFullName();
            }
        ],
        [
            'label' => 'Категория',
            'attribute' => 'position_id', // авто-подключение зависимостей
            'value' => function (Briefing $model) {
                return $model->position->title;
            }
        ],
        [
            'label' => 'Тип',
            'attribute' => 'type',
        ],
        //'id',
        'date_start:date',
        'created_at:datetime',
        'updated_at:datetime',
    ],
]); ?>