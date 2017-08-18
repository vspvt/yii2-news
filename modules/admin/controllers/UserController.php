<?php

namespace app\modules\admin\controllers;

use app\components\UserPermissions;
use app\models\Registration;
use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends DefaultController
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
                        'roles' => [UserPermissions::ROLE_ADMIN],
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
        return $this->renderIndex(new UserSearch);
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
     * @return User
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        return $this->findOneModel(User::className(), $id);
    }

    /**
     * @return string
     */
    public function actionCreate()
    {
        $model = new Registration();
        if ($model->load(Yii::$app->request->post()) && $user_id = $model->registration()) {
            return $this->redirect(['view', 'id' => $user_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionRoles($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->setRoles();
            Yii::$app->session->setFlash('success', 'Success');
        }

        return $this->render('roles', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionPass($id)
    {
        /** @var User $model */
        $model = $this->findModel($id);
        $model->action = 'pass';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->session->setFlash('success', 'Success');
            $model->setNewPassword();
        }

        return $this->render('pass', [
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

}
