<?php

/**
 * @var $this yii\web\View
 * @var $model Comment
 */

use app\models\Comment;
use yii\helpers\Html;
use yii\widgets\DetailView;

?>
    <div class="site-about">
        <h1>Просмотр Комментария</h1>

        <div class="form-group pull-right">
            <?= Html::a('К списку', ['comment/index'], ['class' => 'btn btn-info']) ?>
            <?php if (Yii::$app->user->can('admin')) {
                echo Html::a('Изменить', ['comment/update', 'id' => $model->id], ['class' => 'btn btn-success']);
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
        [
            'label' => 'Кто создал',
            'attribute' => 'name',
        ],
        'email',
        'comment',

        'created_at:datetime',
    ],
]); ?>