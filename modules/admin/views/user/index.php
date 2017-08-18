<?php

use kartik\daterange\DateRangePicker;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('Create user', ['create'], ['class' => 'btn btn-success'])?>
    </p>
    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
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
                'attribute' => 'lastauth_at',
                'value' => 'lastauth_at',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'lastauth_at',
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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete} {roles} {pass}',
                'buttons' => [
                    'roles' => function ($url) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-user"></span>',
                            $url,
                            [
                                'title' => 'Roles',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                    'pass' => function ($url) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-lock"></span>',
                            $url,
                            [
                                'title' => 'Change password',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ],
                'visibleButtons' => [
                    'update' => false,
                ],

            ],
        ],
    ]);?>
</div>
