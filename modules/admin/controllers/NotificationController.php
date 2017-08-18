<?php

namespace app\modules\admin\controllers;

use app\components\UserPermissions;
use app\models\Notification;
use app\models\NotificationSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class NotificationController extends DefaultController
{
    /**
     * @return array
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
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderIndex(new NotificationSearch);
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param int $id
     * @return Notification
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        return $this->findOneModel(Notification::className(), $id);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Notification();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->set();

                return $this->redirect('success');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionSuccess()
    {
        Yii::$app->session->setFlash('success', 'Notification sent');

        return $this->render('success');
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
