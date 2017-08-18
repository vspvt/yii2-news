<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $notification_template_id
 * @property int $notification_type_id
 *
 * @property NotificationType $notificationType
 * @property NotificationTemplate $notificationTemplate
 */
class NotificationTemplatesTypes extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_templates_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notification_template_id', 'notification_type_id'], 'required'],
            [['notification_template_id', 'notification_type_id'], 'integer'],
            [
                ['notification_type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => NotificationType::className(),
                'targetAttribute' => ['notification_type_id' => 'id'],
            ],
            [
                ['notification_template_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => NotificationTemplate::className(),
                'targetAttribute' => ['notification_template_id' => 'id'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'notification_template_id' => 'Notification Template Id',
            'notification_type_id' => 'Notification Type Id',
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
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationTemplate()
    {
        return $this->hasOne(NotificationTemplate::className(), ['id' => 'notification_template_id']);
    }
}
