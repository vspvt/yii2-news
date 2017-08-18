<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_notification_type".
 *
 * @property int $user_id
 * @property int $notification_type_id
 *
 * @property NotificationType $notificationType
 * @property User $user
 */
class UserNotificationType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_notification_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'notification_type_id'], 'required'],
            [['user_id', 'notification_type_id'], 'integer'],
            [
                ['notification_type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => NotificationType::className(),
                'targetAttribute' => ['notification_type_id' => 'id'],
            ],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'notification_type_id' => 'Notification Type ID',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
