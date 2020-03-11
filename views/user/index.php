<?php

/* @var $this yii\web\View */

/**
 * @var $model Position
 * @var $provider ActiveDataProvider
 */

use app\models\Position;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Список работников';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Зарегистрировать нового работника', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
    [
        'class' => SerialColumn::class,
        'header' => 'Порядковый номер',
    ],
    'id',
    'last_name',
    'first_name',
    'third_name',
    'telny_number',
    [
        'label' => 'Должность',
        'attribute' => 'position_id',
        'value' => function (User $model) {
            return $model->position->title;
        }
    ],
    'date_birth:date',
    'date_receipt:date',
    'status:datetime',
    [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'contentOptions' => ['style' => 'text-align:center']
    ],
    ],
])?>

<?php /** GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
//    [
//        'class' => SerialColumn::class,
//        'header' => 'Порядковый номер',
//        'options' => ['style' => 'background-color:#a8b4f2;'],
//    ],
        [
            'attribute' => 'id',
            'options' => ['style' => 'background-color:#a8b4f2;'],
        ],
        [
            'attribute' => 'last_name',
            'options' => ['style' => 'background-color:#a8b4f2;'],
        ],
        [
            'attribute' => 'last_name',
            'options' => ['style' => 'background-color:#a8b4f2;'],
        ],
        [
            'attribute' => 'third_name',
            'options' => ['style' => 'background-color:#a8b4f2;'],
        ],
        [
            'attribute' => 'telny_number',
            'options' => ['style' => 'background-color:#a8b4f2;'],
        ],
        [
            'label' => 'Должность',
            'attribute' => 'position_id',
            'value' => function (User $model) {
                return $model->position->title;
            },
            'options' => ['style' => 'background-color:#a8b4f2;'],
        ],
        [
            'attribute' => 'date_birth', 'format' => ['date'],
            'options' => ['style' => 'background-color:#a8b4f2;'],
        ],
        [
            'attribute' => 'date_receipt', 'format' => ['date'],
            'options' => ['style' => 'background-color:#a8b4f2;'],
        ],
        [
            'attribute' => 'status', 'format' => ['datetime'],
            'options' => ['style' => 'background-color:#a8b4f2;'],
        ],
        [
            'class' => ActionColumn::class,
            'header' => 'Операции',
            'options' => ['style' => 'background-color:#a8b4f2;'],
            'contentOptions' => ['style' => 'text-align:center']
        ],
    ],
])*/?>
</div>