<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotificationTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notification types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-type-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('Create Notification Type', ['create'], ['class' => 'btn btn-success'])?>
    </p>
    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);?>
</div>
