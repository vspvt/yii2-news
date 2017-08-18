<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationTemplate */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Notification Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-template-view">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
        <?=Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
        <?=Html::a('Remove', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Confirm remove of Notification Template?',
                'method' => 'post',
            ],
        ])?>
    </p>

    <?=DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'event.name',
            'title',
            'text:ntext',
        ],
    ])?>

</div>
