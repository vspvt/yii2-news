<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'User password change: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Roles';
?>
<div class="user-update">
    <h1><?=Html::encode($this->title)?></h1>
    <div class="user-form">
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?=Yii::$app->session->getFlash('success')?>
            </div>
        <?php endif ?>
        <?php $form = ActiveForm::begin(); ?>
        <table class="table table-striped">
            <tr>
                <td><?=$model->attributeLabels()['id']?></td>
                <td><?=$model->id?></td>
            </tr>
            <tr>
                <td><?=$model->attributeLabels()['username']?></td>
                <td><?=$model->username?></td>
            </tr>
            <tr>
                <td><?=$model->attributeLabels()['email']?></td>
                <td><?=$model->email?></td>
            </tr>
        </table>
        <?=$form->field($model, 'new_pass')->passwordInput()?>
        <?=$form->field($model, 'new_pass_confirm')->passwordInput()?>
        <div class="form-group">
            <?=Html::submitButton('Update', ['class' => 'btn btn-primary'])?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
