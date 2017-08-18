<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
        <?=Html::a('Create Event', ['create'], ['class' => 'btn btn-success'])?>
    </p>
    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'name',
            'params',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);?>
</div>
