<?php

/**
 * @var $this yii\web\View
 * @var $provider ActiveDataProvider
 * @property User $user
 */

use app\assets\MobileAsset;
use app\models\Activity;
use app\models\User;
use yii\bootstrap\Carousel;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
MobileAsset::register($this);

$columns = [

    [
        'attribute' => 'title',
        'options' => ['style' => 'background-color:rgba(254, 255, 249, 0.8);'],
    ],
    [
        'attribute' => 'date_start', 'format' => ['date'],
        'options' => ['style' => 'background-color:rgba(254, 255, 249, 0.8);'],
    ],

];

if (Yii::$app->user->can('admin')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'options' => ['style' => 'background-color:rgba(254, 255, 249, 0.8);'],
        'contentOptions' => ['style' => 'text-align:center; '],
    ];
} else if (Yii::$app->user->can('user')) {
    $columns[] = [
        'class' => ActionColumn::class,
        'header' => 'Операции',
        'template' => '{view}',
        'options' => ['style' => 'background-color:rgba(254, 255, 249, 0.8);'],
        'contentOptions' => ['style' => 'text-align:center; '],
    ];
}

?>

    <div class="site-about">

        <h1>Список событий</h1>
        <?=
        Yii::$app->user->can('admin') ? (
        Html::a('Создать', ['activity/update'], ['class' => 'btn btn-success pull-right'])
        ) : (
        Html::tag('p', Html::encode('Для просмотра подробного описания нажмите иконку в виде глаза'))
        )
        ?>
    </div>

<?= GridView::widget([
    'dataProvider' => $provider, // $provider->getModels() [....]
    'columns' => $columns,
]) ?>

<div class="site-about" style="margin-bottom: 18px; margin-top: -25px">
    <h1>Новостной блок</h1>
</div>

<div style="display: flex; justify-content: center; margin-bottom: 30px">
    <?php echo Carousel::widget([
        'items' => [

            // the item contains both the image and the caption
            [
                'content' => '<img src="/web/uploads/Gazpromtrans.jpg" alt="" style="width: inherit; height: 165px">',
                'caption' => '<a style="background: #2f55df;border: 1px solid whitesmoke;display: inline-block;margin-bottom: 7px;padding: 5px 15px;text-decoration: none;color: #fefff9;" href="https://trans.gazprom.ru/" target="_blank">Новости Газпромтранса</a>',
                'options' => ['class' => 'slide'],
            ],
            [
                'content' => '<img src="/web/uploads/Gazprom.jpg" alt="" style="width: inherit; height: 165px">',
                'caption' => '<a style="background: #2f55df;border: 1px solid whitesmoke;display: inline-block;margin-bottom: 7px;padding: 5px 15px;text-decoration: none;color: #fefff9;" href="https://www.gazprom.ru/" target="_blank">Официальный сайт</a>',
                'options' => ['class' => 'slide'],
            ],
        ],
        'controls' => [
            Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-left']),
            Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-right']),
        ],
        'options' => ['class' => 'slide', 'style' => "width: inherit; text-align: -webkit-center;"],
    ]);
    ?>
</div>
