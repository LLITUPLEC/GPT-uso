<?php
use app\models\Chat;
use yii\db\ActiveQuery;
use yii\web\View;
/**
 * _@var View $this
 * _@var Chat $message
 * _@var ActiveQuery $messageQuery
 */
?>
<?= $this->render('_chat', compact('messageQuery', 'message')) ?>
