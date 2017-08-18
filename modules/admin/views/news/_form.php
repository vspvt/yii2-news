<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form" style="margin: 20px">
    <?php $form = ActiveForm::begin(); ?>
    <?=$form->field($model, 'title')->textInput(['maxlength' => true])?>
    <?=$form->field($model, 'description')->textInput(['maxlength' => true])?>
    <?=$form->field($model, 'text')->textarea(['rows' => 6])?>
    <?=$form->field($model, 'image')->fileInput()?>

    <?php if (!$model->isNewRecord && $model->image) { ?>
        <img src="<?=$model->image?>" height="100">
    <?php } ?>

    <?=$form->field($model, 'status')->radioList([0 => 'Disabled', 1 => 'Active']);?>

    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
