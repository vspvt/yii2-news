<?php

namespace app\controllers;

use app\models\News;
use yii\filters\AccessControl;

/**
 * Class NewsController
 * @package app\controllers
 */
class NewsController extends AbstractController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionView($id)
    {
        $model = News::findOne(['id' => $id]);

        return $this->render('view.twig', [
            'model' => $model,
        ]);
    }
}
