<?php

use kartik\daterange\DateRangePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?=Html::encode($this->title)?></h1>

    <p>
        <?=\yii\bootstrap\Modal::widget([
            'id' => 'create-modal',
            'toggleButton' => [
                'label' => 'Create post',
                'tag' => 'a',
                'data-target' => '#create-modal',
                'href' => Url::toRoute(['/admin/news/create']),
                'class' => 'btn btn-success',
            ],
            'clientOptions' => false,
        ]);?>
    </p>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'value' => 'id',
                'filter' => false,
            ],
            'title',
            'description',
            [
                'attribute' => 'created_at',
                'value' => 'created_at',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'Y-m-d',
                        ],
                    ],
                ]),
                'format' => 'html',
            ],
            [
                'attribute' => 'status',
                'filter' => [0 => "disabled", 1 => "active"],
                'content' => function ($data) {
                    return '<div class="btn ' . ($data->status ? 'btn-primary' : 'btn-default') . ' btn-xs switch_status" data-href="' . Url::toRoute([
                            '/admin/news/switch-active',
                            'id' => $data->id,
                        ]) . '"><span class="glyphicon ' . ($data->status ? 'glyphicon-eye-open' : 'glyphicon-eye-close') . '"></span></div>';
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return \yii\bootstrap\Modal::widget([
                            'id' => 'update-modal' . $model->id,
                            'toggleButton' => [
                                'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                                'tag' => 'a',
                                'title' => 'Update',
                                'data-target' => '#update-modal' . $model->id,
                                'href' => Url::toRoute([$url]),
                            ],
                            'clientOptions' => false,
                            'options' => [
                                'class' => 'update_news',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]);?>
</div>
