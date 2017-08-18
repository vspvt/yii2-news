<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $lastauth_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{

    # Event will fired when user password was changed
    const EVENT_AFTER_PASSWORD_CHANGE = 'USER_AFTER_PASSWORD_CHANGE';

    # Event will fired when user created
    const EVENT_AFTER_CREATE = 'USER_AFTER_CREATE';

    # Event will fire when user blocked by admin
    const EVENT_AFTER_BLOCK = 'USER_AFTER_BLOCK';

    /** @var string new password */
    public $new_pass;

    /** @var string new password confirmation */
    public $new_pass_confirm;

    /** @var string password confirmation */
    public $pass_confirm;

    /** @var string action */
    public $action;

    /**
     * @return string DB table name
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return User|null
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @param $token
     * @return null|User
     */
    public static function findByPasswordResetToken($token)
    {

        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * @param string $token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * @param string $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @return array validation rules
     */
    public function rules()
    {
        return [
            [
                ['new_pass', 'new_pass_confirm'],
                'required',
                'when' => function (User $model) {
                    return $model->action === 'pass';
                },
                'whenClient' => "function (attribute, value) { return $('#action').val() == 'pass'; }",
            ],
            ['new_pass', 'string', 'min' => 6],
            ['new_pass_confirm', 'newPassConfirmValidate'],
        ];
    }

    /**
     * @return array labels
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'username' => 'Учетное имя',
            'email' => 'E-mail',
            'roles' => 'Роли',
            'new_pass' => 'Новый пароль',
            'new_pass_confirm' => 'Подтверждение нового пароля',
            'notificationTypes' => 'Типы уведомлений',
            'created_at' => 'Дата регистрации',
            'lastauth_at' => 'Последняя авторизация',
        ];
    }

    /**
     * @param $attribute string new_pass_confirm value
     */
    public function newPassConfirmValidate($attribute)
    {
        if ($this->$attribute !== $this->new_pass) {
            $this->addError($attribute, 'Подтверждение пароля не совпадает с новым паролем');
        }
    }

    /**
     * @param $password string password value
     * @return bool
     */
    public function passwordValidate($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @return \yii\db\ActiveQuery Связанные события типа Web
     */
    public function getNewNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id'])
            ->where([
                'notification_type_id' => 2,
                'read' => null,
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery Связанные типы уведомлений
     */
    public function getNotificationTypes()
    {
        return $this
            ->hasMany(NotificationType::className(), ['id' => 'notification_type_id'])
            ->viaTable('user_notification_type', ['user_id' => 'id']);
    }

    /**
     * @return array Вывод всех типов уведомлений
     */
    public function getNotificationTypesAll()
    {
        $model = NotificationType::find()->all();

        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * Сохранение типов уведомлений в юзере
     */
    public function setNotificationTypes()
    {
        $notificationTypes = Yii::$app->request->post()['User']['notificationTypes'];

        $this->unlinkAll('notificationTypes', true);

        if ($notificationTypes) {
            foreach ($notificationTypes as $notificationType) {
                $this->link('notificationTypes', NotificationType::findOne(['id' => $notificationType]));
            }
        }
    }

    /**
     * @return ActiveQuery Связанные роли
     */
    public function getRoles()
    {
        return $this
            ->hasMany(AuthItem::className(), ['name' => 'item_name'])
            ->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * @return AuthItem[]
     */
    public function getRolesAll()
    {
        $model = AuthItem::find()->all();

        return ArrayHelper::map($model, 'name', 'name');
    }

    /**
     * link Roles or bind events
     */
    public function setRoles()
    {
        $roles = Yii::$app->request->post()['User']['roles'];

        $this->unlinkAll('roles', true);

        if ($roles) {
            foreach ($roles as $role) {
                $this->link('roles', AuthItem::findOne(['name' => $role]));
            }
        } else {
            $this->on(User::EVENT_AFTER_BLOCK, [$this, 'sendNotification'], [
                'code' => User::EVENT_AFTER_BLOCK,
                'user_id' => $this->id,
                'user_email' => $this->email,
                'params' => [
                    'sitename' => Yii::$app->id,
                    'username' => $this->username,
                ],
            ]);

            $this->trigger(User::EVENT_AFTER_BLOCK);
        }
    }

    /**
     * Смена пароля
     * @throws \yii\base\Exception
     */
    public function setNewPassword()
    {
        $this->password = Yii::$app->security->generatePasswordHash($this->new_pass);
        $this->save();

        $this->on(User::EVENT_AFTER_PASSWORD_CHANGE, [$this, 'sendNotification'], [
            'code' => User::EVENT_AFTER_PASSWORD_CHANGE,
            'user_id' => $this->id,
            'user_email' => $this->email,
            'params' => [
                'sitename' => Yii::$app->id,
                'username' => $this->username,
                'newPass' => $this->new_pass,
            ],
        ]);

        $this->trigger(User::EVENT_AFTER_PASSWORD_CHANGE);
    }

    /**
     * Отправка уведомлений по шаблону юзеру и админу
     * @param $event
     * @throws HttpException
     */
    public function sendNotification($event)
    {
        $modelEvent = Event::findOne(['code' => $event->data['code']]);

        /** @var NotificationTemplate $template */
        $template = NotificationTemplate::findOne([
            'event_id' => $modelEvent->id,
            'duty' => null,
        ]);

        if ($template !== null && $template->notificationTypes) {
            foreach ($template->notificationTypes as $notificationType) {
                $notification = new Notification;
                $notification->saveData([
                    'title' => NotificationTemplate::decode($template->title, $event->data['params']),
                    'text' => NotificationTemplate::decode($template->text, $event->data['params']),
                    'user_id' => $event->data['user_id'],
                    'user_email' => $event->data['user_email'],
                    'notification_type_id' => $notificationType->id,
                ]);
            }
        }

//        Шаблон  уведомления для админа
        $template = NotificationTemplate::findOne([
            'event_id' => $modelEvent->id,
            'duty' => 1,
        ]);

        if ($template !== null && $template->notificationTypes) {
            foreach ($template->notificationTypes as $notificationType) {
                foreach (User::getUsersByRole('admin') as $admin) {
                    $notification = new Notification;
                    $notification->saveData([
                        'title' => NotificationTemplate::decode($template->title, $event->data['params']),
                        'text' => NotificationTemplate::decode($template->text, $event->data['params']),
                        'user_id' => $admin['id'],
                        'user_email' => $admin['email'],
                        'notification_type_id' => $notificationType->id,
                    ]);
                }
            }
        }
    }

    /**
     * @param $role string Role name
     * @return array Users list with role
     */
    public static function getUsersByRole($role)
    {
        $query = new Query();

        return $query->select('*')
            ->from('auth_assignment')
            ->leftJoin('user', 'auth_assignment.user_id = user.id')
            ->where(['auth_assignment.item_name' => $role])
            ->all();
    }

    /**
     * @return int current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);

        return $this;
    }

    /**
     * Generates "remember me" authentication key
     *
     * @return User
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();

        return $this;
    }

    /**
     * @return User
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();

        return $this;
    }

    /**
     * @return User
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;

        return $this;
    }

}
