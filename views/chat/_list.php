<?php

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * _@var View $this
 * _@var ActiveQuery $messageQuery
 */
?>
<div class="site-about">
    <div class="">
        <?php if (Yii::$app->user->can('admin')) {
            echo Html::a('Бан-лист', ['chat/view'], ['class' => 'btn btn-info']);
        } ?>
    </div>
</div>
<?php Pjax::begin() ?>
<?= ListView::widget([
    'itemView' => '_row',
    'dataProvider' => new ActiveDataProvider([
        'query' => $messageQuery,
        'pagination' => [
            'pageSize' => 12,
        ],
    ]),
])?>
<?php Pjax::end() ?>

