<?php

/**
 * @var $this yii\web\View
 * @var $model Activity
 */

use app\models\Activity;
use yii\helpers\Html;
use yii\widgets\DetailView;

Activity::markActionRead($model->id);

?>
    <div class="site-about">
        <h1>Просмотр события</h1>

        <div class="form-group pull-right">
            <?= Html::a('К списку', ['activity/index'], ['class' => 'btn btn-info']) ?>
            <?php if (Yii::$app->user->can('admin')) {
                echo Html::a('Изменить', ['activity/update', 'id' => $model->id], ['class' => 'btn btn-success']);
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
//        [
//            // activity.id - пример перезаписи значения
//            'label' => 'Порядковый номер',
//            'value' => function (Activity $model) {
//                return "# {$model->id}";
//            },
//        ],
        //'id',
        'title',
        'date_start:date',
        'date_end:date',
//        [
//            // так делать плохо, лучше как ниже (авто-форматирование)
//            'attribute' => 'date_end',
//            'value' => function (Activity $model) {
//                return Yii::$app->formatter->asDate($model->date_end, 'php:Y');
//            }
//        ],
//        [
//            'attribute' => 'date_end',
//            'format' => ['date', Yii::$app->params['dateFormat']], // форматирование даты (из params)
//            //'value' => function (Activity $model) {
//            //    return Yii::$app->formatter->asDate($model->date_end, 'php:Y');
//            //}
//        ],
        //'user_id',
        [
            'label' => 'Кто создал',
            'attribute' => 'user.last_name', // авто-подключение зависимостей
            // $model->user->username
        ],
        'description',
//        'repeat:boolean', // Yii::$app->formatter->asBoolean(...)
//        'blocked:boolean',
        'created_at:datetime',
        'updated_at:datetime',
    ],
]); ?>