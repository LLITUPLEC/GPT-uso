<?php

use app\models\Activity;
use app\models\Briefing;
use app\models\File;
use app\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\YiiAsset;

$this->title = Html::encode(Yii::$app->user->identity->username);
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);

$user_ID = Yii::$app->user->id;

$userActivity = (new Activity)->getActualActivities($user_ID);
$userDocs = (new File)->getActualDocs($user_ID);
$userBrief = (new Briefing)->getActualBriefings($user_ID);

$countUserActivity = null;
$countUserDocs = null;
$countUserBrief = null;

    if (count($userActivity) == null) {
        $countUserActivity = 0;
    } else {
        $countUserActivity = count($userActivity);
    }

    if (count($userDocs) == null) {
        $countUserDocs = 0;
    } else {
        $countUserDocs = count($userDocs);
    }

    if (count($userBrief) == null) {
        $countUserBrief = 0;
    } else {
        $countUserBrief = count($userBrief);
    }

    ?>

<h1>Страница пользователя  <u><?= Html::encode(Yii::$app->user->identity->username)?></u></h1>

<h3><?= Html::encode(Yii::$app->user->identity->first_name)?>,

    <?= Html::submitButton('можете изменить пароль', ['id' => 'changePass', 'class' => 'btn btn-primary btn-sm']) ?></h3>

<div class="container">
    <div id="changePassSlider" class="col-lg-3" style="display: none">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'reenter_password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Применить', ['id' => 'changedPass', 'class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

<div>
    <h4>
        Посмотреть результаты теста можно  <?= Html::a('по этой ссылке', ['result/index'], ['class' => 'btn btn-info'])?>
    </h4>
</div>

<div class="accordion" id="accordionExample">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <span class="i_new_count">Новых событий: &nbsp;&nbsp;</span><span id="i_new_action" class="badge badge-primary badge-pill"><?php echo $countUserActivity ?></span>
                </button>
            </h5>
        </div>

        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body">
                <ul>
                    <?php foreach ($userActivity as $item => $name) { ?>
                        <li><a href="../activity/view?id=<?=$name['id']?>"><?=$name['title']?></a></li>
                    <?php } ?>
                </ul>

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <span class="i_new_count">Новых документов: &nbsp;&nbsp;</span><span id="i_new_doc" class="badge badge-primary badge-pill"><?php echo $countUserDocs ?></span>
                </button>
            </h5>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
            <div class="card-body">
                <ul>
                    <?php foreach ($userDocs as $item => $name) { ?>
                        <li><a href="../file/view?id=<?=$name['id']?>"><?=$name['title']?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingThree">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <span class="i_new_count">Новых инструктажей/проверок: &nbsp;&nbsp;</span><span id="i_new_brief" class="badge badge-primary badge-pill"><?php echo $countUserBrief ?></span>
                </button>
            </h5>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body">
                <ul>
                    <?php foreach ($userBrief as $item => $name) { ?>
                        <li><a href="../briefing/view?id=<?=$name['id']?>"><?=$name['title']?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->registerJsFile('../../web/js/changePassSlide.js') ?>

