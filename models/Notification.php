<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property string $title
 * @property string $text
 * @property int $user_id
 * @property int $notification_type_id
 * @property int $read
 *
 * Связи
 * @property NotificationType $notificationType
 * @property User $user Юзеры
 */
class Notification extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['notification_type_id', 'read'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [
                ['notification_type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => NotificationType::className(),
                'targetAttribute' => ['notification_type_id' => 'id'],
            ],
            [['title', 'text', 'user_id', 'notification_type_id'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'read' => 'Прочитано',
            'notification_type_id' => 'Тип уведомления',
            'user_id' => 'Пользователь',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationType()
    {
        return $this->hasOne(NotificationType::className(), ['id' => 'notification_type_id']);
    }

    /**
     * @return array
     */
    public function getNotificationTypes()
    {
        $model = NotificationType::find()->all();

        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        $qb = new Query();
        $users = $qb
            ->select('id, username')
            ->from('user')
            ->leftJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->where(['auth_assignment.item_name' => 'user'])
            ->all();

        return ArrayHelper::map($users, 'id', 'username');
    }

    /**
     * Setting and sending notifications
     */
    public function set()
    {
        $users = User::findAll(['id' => (array)$this->user_id]);
        foreach ($users as $user) {
            $data = [
                'title' => $this->title,
                'text' => $this->text,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'notification_type_id' => $this->notification_type_id,
            ];

            $this->saveData($data);
        }

    }

    /**
     * Sending notifications
     * @param $data
     */
    public function saveData($data)
    {
        $count = UserNotificationType::find()
            ->where([
                'user_id' => $data['user_id'],
                'notification_type_id' => $data['notification_type_id'],
            ])
            ->count();

        # User has subscribed notifications
        if ($count) {
            $notification = new Notification();
            $notification->title = $data['title'];
            $notification->text = $data['text'];
            $notification->user_id = $data['user_id'];
            $notification->notification_type_id = $data['notification_type_id'];
            $notification->save();

            # email notifications
            if ($data['notification_type_id'] == 1) {
                Yii::$app->mailer->compose()
                    ->setFrom(env('MAIL_FROM_EMAIL'))
                    ->setTo($data['user_email'])
                    ->setSubject($data['title'])
                    ->setHtmlBody($data['text'])
                    ->send();
            }
        }
    }

    /**
     * @return bool
     */
    public function setReaded()
    {
        $this->read = true;

        return $this->save();
    }

    /**
     * @return bool
     */
    public function isReaded()
    {
        return filter_var($this->read, FILTER_VALIDATE_BOOLEAN);
    }
}
