<?php

namespace app\controllers;

use app\models\News;
use yii\data\Pagination;
use yii\widgets\LinkPager;

class SiteController extends AbstractController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = News::find();

        $limit = $this->getLimit();
        $offset = $this->getOffest($limit);
        $pagination = new Pagination([
            'totalCount' => $model->count(),
            'pageSize' => $limit,
        ]);

        return $this->render('index.twig', [
            'model' => $model
                ->limit($limit)
                ->offset($offset)
                ->orderBy('id DESC'),
            'pager' => LinkPager::widget([
                'pagination' => $pagination,
            ]),
        ]);
    }

}
