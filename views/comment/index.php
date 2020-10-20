<?php
use app\models\Comment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'enableClientValidation' => true,
    'enableAjaxValidation'   => false,
    'action' => Url::to(['comment/index']),
    'method' => 'post',
    'options' => ['id' => 'form']
]) ?>
<?= $form->field($model, 'note')->textarea(['rows' => 5]); ?>
<?= Html::submitButton("Submit", ['class' => "btn btn-default"]); ?>
<?php ActiveForm::end() ?>
    <div id="process">
        <img src="/web/uploads/loading.gif" alt="Loading">
    </div>
    <h2>Comments</h2>
    <div id="comments">
        <?
        /**
         * @var $comments
         * @var $item Comment
         */
        foreach ($comments as $item):
            ?>
            <div class="comment"><?= $item->note ?></div>
        <? endforeach; ?>
    </div>
<?php
$js = <<<JS
$('#form').on('beforeSubmit', function(){        
  var form = $(this),
    data = $(this).serialize();
  $.ajax({
    url: form.attr("action"),
    type: form.attr("method"),
    data: data,
    beforeSend: function(){
      $('#process').fadeIn();
    },
    success: function(data){
      form[0].reset();
      $("#comments").append('<div class="comment">'+ data.note +'</div>');
      $('#process').fadeOut();
    },
    error: function(){
      $('#process').fadeOut();
      alert('Error!');
    }
  });
  return false;
}).on('submit', function(e){
  e.preventDefault();
});
JS;
$this->registerJs($js);
?>