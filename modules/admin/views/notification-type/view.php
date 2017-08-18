<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Notification Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-type-view">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
        <?=Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
        <?=Html::a('Remove', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Remove selected Notification Type?',
                'method' => 'post',
            ],
        ])?>
    </p>

    <?=DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
        ],
    ])?>

</div>
