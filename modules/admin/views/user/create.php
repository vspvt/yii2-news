<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Create user';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?=Html::encode($this->title)?></h1>

    <div class="user-form">
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?=Yii::$app->session->getFlash('success')?>
            </div>
        <?php endif ?>
        <?php $form = ActiveForm::begin(); ?>
        <?=$form->field($model, 'username')->textInput(['maxlength' => true])?>
        <?=$form->field($model, 'email')->textInput(['maxlength' => true])?>
        <?=$form->field($model, 'password')->passwordInput(['maxlength' => true])?>
        <?=$form->field($model, 'password_confirm')->passwordInput(['maxlength' => true])?>
        <div class="form-group">
            <?=Html::submitButton('Create', ['class' => 'btn btn-primary'])?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
