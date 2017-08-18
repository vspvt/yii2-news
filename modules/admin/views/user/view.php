<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
        <?=Html::a('User roles', ['roles', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
        <?=Html::a('Change passowrd', ['pass', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
        <?=Html::a('Remove', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Remove user?',
                'method' => 'post',
            ],
        ])?>
    </p>

    <?=DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
        ],
    ])?>

</div>
