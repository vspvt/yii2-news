<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('Create Notification', ['create'], ['class' => 'btn btn-success'])?>
    </p>
    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'text:ntext',
            ['attribute' => 'User', 'value' => 'user.username'],
            ['attribute' => 'Notification Type', 'value' => 'notificationType.name'],
            'read',
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => false,
                ],
            ],
        ],
    ]);?>
</div>
