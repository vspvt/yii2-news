<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 *
 * @property Notification[] $notifications
 * @property NotificationTemplatesTypes[] $notificationTemplateNotificationTypes
 * @property NotificationTemplate[] $notificationTemplates
 * @property UserNotificationType[] $userNotificationTypes
 * @property User[] $users Юзеры
 */
class NotificationType extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'notification_type';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 32],
            [['name'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'name' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['notification_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationTemplates()
    {
        return $this
            ->hasMany(NotificationTemplate::className(), ['id' => 'notification_template_id'])
            ->viaTable('notification_templates_types', ['notification_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this
            ->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('user_notification_type', ['notification_type_id' => 'id']);
    }
}
