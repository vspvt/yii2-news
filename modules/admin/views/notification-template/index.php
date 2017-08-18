<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotificationTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notification Templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-template-index">

    <h1><?=Html::encode($this->title)?></h1>
    <p><?=Html::a('Create Notification Template', ['create'], ['class' => 'btn btn-success'])?></p>
    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Event',
                'value' => 'event.name',
            ],
            'title',
            'text:ntext',
            'duty',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);?>
</div>
