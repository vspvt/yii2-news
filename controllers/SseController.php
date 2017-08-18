<?php

namespace app\controllers;

use app\models\Notification;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class SseController extends Controller
{

    public function actionIndex($id)
    {
        $response = new Response();
        if ((int)Yii::$app->user->identity->getId() === (int)$id) {
            $response->headers
                ->add('Content-Type', 'text/event-stream')
                ->add('Cache-Control', 'no-cache');
            $notifications = Notification::find()
                ->where([
                    'user_id' => Yii::$app->user->identity->getId(),
                    'read' => null,
                    'notification_type_id' => 2,
                ])
                ->orderBy(['id' => 'DESC'])
                ->all();
            $result = [];
            foreach ($notifications as $notification) {
                $result[] = [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'text' => $notification->text,
                ];
            }
            $response->statusCode = 200;
            $dataJson = json_encode($result);
            $response->content =
                "id: {$id}" . PHP_EOL
                . "data: {$dataJson}" . PHP_EOL
                . \PHP_EOL;
        } else {
            $response->statusCode = 404;
        }

        return $response;
    }

}
