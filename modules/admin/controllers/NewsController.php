<?php

namespace app\modules\admin\controllers;

use app\components\UserPermissions;
use app\models\News;
use app\models\NewsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class NewsController extends DefaultController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [UserPermissions::ROLE_ADMIN, UserPermissions::ROLE_MODERATOR],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => [UserPermissions::PERMISSION_UPDATE_POST],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        return $this->renderIndex(new NewsSearch);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param int $id
     * @return News
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        return $this->findOneModel(News::className(), $id);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->on(News::EVENT_AFTER_CREATE, [$model, 'sendNotification'], [
                'code' => News::EVENT_AFTER_CREATE,
            ]);
            $model->trigger(News::EVENT_AFTER_CREATE);

            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $model->upload();
            }
            $response = \Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            return [];
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (\Yii::$app->user->can('updatePost', ['post' => $model])) {
            if ($model->load(Yii::$app->request->post())) {
                $model->save();
                $model->image = UploadedFile::getInstance($model, 'image');
                if ($model->image) {
                    $model->upload();
                }
                \Yii::$app->response->format = Response::FORMAT_JSON;

                return [];
            }

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }

        return $this->renderAjax('update_permission');
    }

    /**
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    public function actionSwitchActive($id)
    {
        $model = $this->findModel($id);
        $model->status = (int)!$model->status;
        $model->save();
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [];
    }
}
