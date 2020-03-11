<?php

/**
 * @var $this yii\web\View
 * @var $provider ActiveDataProvider
 * @property User $user
 */

use app\models\Activity;
use app\models\User;
use yii\bootstrap\Carousel;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$columns = [
    //[
    //    'class' => SerialColumn::class,
    //    'header' => 'Псевдо-порядковый номер',
    //],
    //[
    //    // activity.id - пример перезаписи названия столбца
    //    'label' => 'Порядковый номер',
    //    'attribute' => 'id',
    //],
    [
        // activity.id - пример перезаписи значения
        'label' => 'Порядковый номер',
        'value' => function (Activity $model) {
            return "# {$model->id}";
        },
    ],
    //'id',
    'title',
    'date_start:date',
    'date_end:date',
    //'user_id',
    [
        'label' => 'Кто создал',
        'attribute' => 'user_id', // авто-подключение зависимостей
        'value' => function (Activity $model) {
            return $model->user->last_name;
        }
        // $model->user->username
    ],
//    'repeat:boolean', // Yii::$app->formatter->asBoolean(...)
//    'blocked:boolean',
    'created_at:datetime',
    'updated_at:datetime',
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

    <div class="site-about" style="margin-top: -50px;margin-bottom: 30px;">
        <h1>Список событий</h1>
        <?=
        Yii::$app->user->can('admin') ? (
        Html::a('Создать', ['activity/update'], ['class' => 'btn btn-success pull-right'])
        ) : (
        Html::tag('p', Html::encode('Для просмотра подробного описания нажмите иконку в виде глаза'))
        )
        ?>
    </div><br>

<?= GridView::widget([
    'dataProvider' => $provider, // $provider->getModels() [....]
    'columns' => $columns,
]) ?>

<div class="site-about" style="margin-bottom: 18px; margin-top: -30px;">
    <h1>Новостной блок</h1>
</div>

<div style="display: flex; justify-content: center;">
    <?php echo Carousel::widget([
        'items' => [

            // the item contains both the image and the caption
            [
                'content' => '<img src="/web/uploads/Gazpromtrans.jpg" alt="" style="width: 670px; height: 265px">',
                'caption' => '<a style="background: #2f55df;border: 1px solid whitesmoke;display: inline-block;margin-bottom: 7px;padding: 5px 15px;text-decoration: none;color: #fefff9;" href="https://trans.gazprom.ru/" target="_blank">Новости Газпромтранса</a>',
                'options' => ['class' => 'slide'],
            ],
            [
                'content' => '<img src="/web/uploads/Gazprom.jpg" alt="" style="width: 670px; height: 265px">',
                'caption' => '<a style="background: #2f55df;border: 1px solid whitesmoke;display: inline-block;margin-bottom: 7px;padding: 5px 15px;text-decoration: none;color: #fefff9;" href="https://www.gazprom.ru/" target="_blank">Официальный сайт</a>',
                'options' => ['class' => 'slide'],
            ],
        ],
        'controls' => [
            Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-left']),
            Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-right']),
        ],
        'options' => ['class' => 'slide', 'style' => "width: 670px; text-align: -webkit-center;"],
    ]);
    ?>
</div>
