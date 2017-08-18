<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\Html;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Class News
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $text
 * @property string $image
 * @property string $created_at
 * @property int $status
 * @package app\models
 */
class News extends ActiveRecord
{
   const EVENT_AFTER_CREATE = 'NEWS_AFTER_CREATE';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['text'], 'string'],
            [['status'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['status'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'text' => 'Текст',
            'image' => 'Картинка',
            'created_at' => 'Дата создания',
            'status' => 'Статус',
        ];
    }

    /**
     * @return boolean
     * */
    public function upload()
    {
        if (!$this->validate()) {
            return false;
        }
        /** @var UploadedFile $uploadedImage */
        $uploadedImage = $this->image;
        $path = 'uploads/' . $uploadedImage->baseName . '.' . $uploadedImage->extension;
        $uploadedImage->saveAs($path);

        $this->image = '/' . $path;
        $this->save();

        return true;
    }

    /**
     * @param \yii\base\Event $event
     * @throws HttpException
     */
    public function sendNotification($event)
    {
        /** @var Event $modelEvent */
        $modelEvent = Event::findOne(['code' => $event->data['code']]);
        /** @var NotificationTemplate $template */
        $template = NotificationTemplate::findOne(['event_id' => $modelEvent->id]);

        if ($template->notificationTypes) {
            foreach ($template->notificationTypes as $notificationType) {
                $qb = new Query();
                $user_subscribed = $qb
                    ->select('*')
                    ->from('user')
                    ->leftJoin('user_notification_type', 'user.id = user_notification_type.user_id')
                    ->where(['user_notification_type.notification_type_id' => $notificationType->id])
                    ->groupBy(['user.id'])
                    ->all();

                foreach ($user_subscribed as $user) {
                    $notification = new Notification;
                    $params = [
                        'sitename' => Yii::$app->id,
                        'username' => $user['username'],
                        'title' => $this->title,
                        'link' => Html::a('Ссылка', '/news/view?id=' . $this->id),
                    ];
                    $notification->saveData([
                        'title' => NotificationTemplate::decode($template->title, $params),
                        'text' => NotificationTemplate::decode($template->text, $params),
                        'user_id' => $user['id'],
                        'user_email' => $user['email'],
                        'notification_type_id' => $notificationType->id,
                    ]);
                }
            }
        }
    }
}
