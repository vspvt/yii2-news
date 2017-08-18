<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property int $event_id
 * @property string $title
 * @property string $text
 *
 * @property Event $event
 * @property NotificationTemplatesTypes[] $notificationTemplateNotificationTypes
 * @property NotificationType[] $notificationTypes
 */
class NotificationTemplate extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'notification_template';
    }

    /**
     * @param $string string
     * @param $data mixed
     * @return string
     */
    public static function decode($string, $data)
    {
        preg_match_all('|{(.+?)}|is', $string, $matches);

        for ($i = 0; $i < count($matches[1]); $i++) {
            $string = str_replace($matches[0][$i], $data[str_replace(['{', '}'], '', $matches[0][$i])], $string);
        }

        return $string;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['event_id'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 128],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
            [['event_id', 'title', 'text'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'event_id' => 'Id События',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'notificationTypes' => 'Типы уведомлений',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * return Event names with id as key
     * @return array
     */
    public function getEvents()
    {
        $model = Event::find()->all();

        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationTypes()
    {
        return $this->hasMany(NotificationType::className(), ['id' => 'notification_type_id'])
            ->viaTable('notification_templates_types', ['notification_template_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getNotificationTypesAll()
    {
        $model = NotificationType::find()->all();

        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $post = Yii::$app->request->post();
        $notification_types = NotificationType::findAll(['id' => $post['NotificationTemplate']['notificationTypes']]);

        $this->unlinkAll('notificationTypes', true);

        foreach ($notification_types as $notification_type) {
            $this->link('notificationTypes', $notification_type);
        }
    }
}
