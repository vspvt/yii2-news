<?php

namespace app\modules\admin\controllers;

use app\components\UserPermissions;
use app\models\Event;
use app\models\EventSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class EventController extends DefaultController
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
     * view Events list
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderIndex(new EventSearch);
    }

    /**
     * view Event
     *
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
     * @return Event
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        return $this->findOneModel(Event::className(), $id);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Event();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        /** @var Event $model */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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

    public function actionParams()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));

        Yii::$app->response->content = json_encode($model->params);
    }

}
