<?php

/* @var $this View */

/* @var $content string */

use app\models\Result;
use app\models\User;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
if (!\Yii::$app->getUser()->isGuest) {
    Result::userNeedTests();
}

if (Yii::$app->user->can('user')) {
    User::getCountInfo();
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
if (Yii::$app->controller->id == 'site') {
    echo '<div class="wrap"  style="background: url(\'/web/uploads/123.jpg\') no-repeat; background-size: 100%">';
} else if ((Yii::$app->controller->id == 'user') && (Yii::$app->controller->action->id !== 'index')){
    echo '<div class="wrap"  style="background: url(\'/web/uploads/123.jpg\') no-repeat; background-size: 100%">';
} else if ((Yii::$app->controller->id == 'file') && (Yii::$app->controller->action->id == 'index' || 'view')){
    echo '<div class="wrap"  style="background: url(\'/web/uploads/123.jpg\') no-repeat; background-size: 100%">';
} else if ((Yii::$app->controller->id == 'activity')){
    echo '<div class="wrap"  style="background: url(\'/web/uploads/123.jpg\') no-repeat; background-size: 100%">';
} else if ((Yii::$app->controller->id == 'result')){
    echo '<div class="wrap"  style="background: url(\'/web/uploads/123.jpg\') no-repeat; background-size: 100%">';
} else if ((Yii::$app->controller->id == 'briefing')){
    echo '<div class="wrap"  style="background: url(\'/web/uploads/123.jpg\') no-repeat; background-size: 100%">';
} else {
    echo '<div class="wrap">';
}
?>

<?php
NavBar::begin([
    'brandLabel' => Html::img(Yii::$app->homeUrl . 'uploads//54321.png', ['style' => 'width:150px; margin-top:-14px; height:inherit']),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => 'Главная', 'url' => ['/site/index']],

        ['label' => 'Пройти тест', 'url' => ['/test/index'],
            'visible' => Yii::$app->user->can('user') &&
                Result::$testForUser[0] !== null],
        ['label' => 'Работники', 'url' => ['/user/index'], 'visible'=>Yii::$app->user->can('admin')],
        ['label' => 'События', 'url' => ['/activity/index']],
        [
           'label' => 'Работа с тестами',
           'items' => [
                ['label' => 'Список тестов', 'url' => ['/testing/test']],
                ['label' => 'Список вопросов', 'url' => ['/testing/question']],
                ['label' => 'Список ответов', 'url' => ['/testing/answer']],
            ], 'visible'=>Yii::$app->user->can('admin')
        ],
        [
            'label' => '<span>Личный кабинет&nbsp;<span id="badge_target" class="badge badge-info">'
                . User::$countInfo . '</span></span>',
            'encode' => false,
            'url' => ['/user/user_homepage?id=' . Yii::$app->user->id],
            'visible' => Yii::$app->user->can('user'),
        ],

        Yii::$app->user->isGuest ? (
        ['label' => 'Войти', 'url' => ['/site/login']]
        ) : (
            '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выйти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>'
        )
    ],
]);
NavBar::end();
?>

<div class="container" style="margin-top: 50px">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>
</div>
</div>

<footer class="footer" ;
">
<div class="container">
    <p class="pull-left">&copy; АФ ООО <b>"Газпромтранс"</b> <?= date('Y') ?></p>
</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
