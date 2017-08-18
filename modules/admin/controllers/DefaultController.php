<?php

namespace app\modules\admin\controllers;

use app\components\UserPermissions;
use app\models\EventSearch;
use app\models\NewsSearch;
use app\models\NotificationSearch;
use app\models\NotificationTemplateSearch;
use app\models\NotificationTypeSearch;
use app\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DefaultController extends Controller
{
    public $layout = 'main.twig';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
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
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect(Yii::$app->user->loginUrl[0] . '?returnURL=' . URL::current());
        }

        return parent::beforeAction($action);
    }

    /**
     * @return Response
     */
    public function actionIndex()
    {
        return $this->redirect('/admin/news');
    }

    /**
     * @param EventSearch|NewsSearch|NotificationSearch|NotificationTemplateSearch|NotificationTypeSearch|UserSearch $searchModel
     * @return string
     */
    protected function renderIndex($searchModel)
    {
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param string $className
     * @param int $id
     * @param bool $throwException
     * @throws NotFoundHttpException
     * @return mixed
     */
    protected function findOneModel($className, $id, $throwException = true)
    {
        $model = call_user_func([$className . '::findOne'], $id);
        if (!$model) {
            if ($throwException) {
                throw new NotFoundHttpException();
            }
            $model = null;
        }

        return $model;
    }

}
