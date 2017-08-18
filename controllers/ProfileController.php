<?php

namespace app\controllers;

use app\models\Notification;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class ProfileController
 * @package app\controllers
 */
class ProfileController extends Controller
{
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        if ($user->load(Yii::$app->request->post())) {
            $user->setNotificationTypes();
            Yii::$app->session->setFlash('success', 'Success');
        }

        return $this->render('index.twig');
    }

    public function actionNotyRead($id)
    {
        $noty = Notification::findOne($id);
        !$noty or $noty->setReaded();
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [];
    }

}
