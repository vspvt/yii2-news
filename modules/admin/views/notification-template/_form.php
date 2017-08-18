<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notification-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=$form->field($model, 'event_id')->dropDownList($model->events);?>

    <?=$form->field($model, 'title')->textInput(['maxlength' => true])?>

    <div>Supported items: <em class="set-event-params"></em></div>

    <hr>

    <?=$form->field($model, 'text')->textarea(['rows' => 6])?>

    <div>Supported items: <em class="set-event-params"></em></div>

    <hr>

    <?=$form->field($model, 'notificationTypes')->checkboxList($model->notificationTypesAll);?>

    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
    $(function(){
        notificationtemplate_event_id = $('#notificationtemplate-event_id');
        getEventParams(notificationtemplate_event_id.val());
        notificationtemplate_event_id.change(function(){
            getEventParams($(this).val());
        });
        function getEventParams(eventId) {
            $.post('/admin/event/params', {id: eventId}, function(data){
                object = $.parseJSON(data);
                $('.set-event-params').html(object);
            });
        }
    });
JS;
$this->registerJs($script);
?>
