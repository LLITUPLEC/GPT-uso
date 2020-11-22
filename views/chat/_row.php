<?php

use app\models\Chat;
use yii\helpers\Html;
use yii\web\View;
/**
 * _@var View $this
 * _@var Chat $model
 */
?>
<div class="list-group" style="display: flex; align-items: baseline; <?php if ($model->blocked == 1)
    echo "background: #d24811; color: yellow"?>" >
    <?=
    $model->getRole($model->user_id) === 'admin' && $model->blocked !== 1 ? (
    Html::tag('p',$model->user->first_name, ['class' => 'col-md-3 bg-info'])
    ) : (
    Html::tag('p', Html::encode($model->user->first_name),['class' => 'col-md-3'])
    )
    ?>
    <?=
    $model->getRole($model->user_id) === 'admin' && $model->blocked !== 1  ? (
    Html::tag('p',$model->message, ['class' => 'col-md-4 bg-info'])
    ) : (
    Html::tag('p', Html::encode($model->message),['class' => 'col-md-4'])
    )
    ?>
    <p class="col-md-4"><?= $model->getDate() ?></p>

    <?php if (Yii::$app->user->can('admin') && $model->blocked !== 1) {
        echo Html::a('x', ['chat/update', 'id' => $model->id], ['class' => 'btn btn-danger']);
    } else if (Yii::$app->user->can('admin') && $model->blocked !== 0){
        echo Html::a('+', ['chat/update', 'id' => $model->id], ['class' => 'btn btn-success']);
    } ?>
</div>
