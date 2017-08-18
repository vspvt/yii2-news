<?php

namespace app\controllers;

use app\models\Login;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Auth controller
 *
 * Class LoginController
 * @package app\controllers
 */
class LoginController extends Controller
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
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['out'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Authorization action
     */
    public function actionIndex()
    {
        $returnURL = Yii::$app->request->get('returnURL', null);
        $model = new Login;

        if (!Yii::$app->user->isGuest || ($model->load(Yii::$app->request->post()) && $model->login())) {
            return $this->goBack($returnURL);
        }

        return $this->render('index.twig', [
            'model' => $model,
        ]);
    }

    /**
     * Logout
     * @return \yii\web\Response
     */
    public function actionOut()
    {
        Yii::$app->user->isGuest or Yii::$app->user->logout();

        return $this->goHome();
    }

}
