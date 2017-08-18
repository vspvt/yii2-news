<?php

namespace app\controllers;

use app\models\Login;
use app\models\Registration;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class RegistrationController
 * @package app\controllers
 */
class RegistrationController extends Controller
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
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => false,
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
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Registration;
        if ($model->load(Yii::$app->request->post()) && $model->registration()) {
            return $this->redirect(URL::home() . 'registration/success');
        }

        return $this->render('index.twig', [
            'model' => $model,
        ]);
    }

    /**
     * Registration success
     *
     * @return string
     */
    public function actionSuccess()
    {
        Yii::$app->session->setFlash('success', 'Check e-mail to activate account');

        return $this->render('success.twig');
    }

    /**
     * User activation
     */
    public function actionActivation()
    {
        $model = new Registration;

        $hash = Yii::$app->request->get('hash', null);
        /** @var User $user */
        $user = $model->activation($hash);

        $login = new Login();
        $login->username = $user->username;
        $login->login(true);

        return $this->goHome();
    }

}
