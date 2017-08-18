<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\HttpException;

class Registration extends Model
{

    /** @var string */
    public $username;

    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var string */
    public $password_confirm;

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'password_confirm' => 'Подтверждение пароля',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'password_confirm'], 'required'],
            [['username', 'email'], 'trim'],
            [['username', 'email'], 'string', 'max' => 32],
            ['email', 'email'],
            [['username', 'email'], 'uniqueValidate'],
            [['password'], 'string', 'min' => 6],
            ['password_confirm', 'passwordConfirmValidate'],
        ];
    }

    /**
     * @param $attribute string
     */
    public function uniqueValidate($attribute)
    {
        $user = User::findOne([$attribute => $this->$attribute]);

        if ($user) {
            if ($user->id !== null) {
                $this->addError($attribute, 'Пользователь с таким ' . $attribute . ' уже есть в базе.');
            }
        }
    }

    /**
     * @param $attribute string
     */
    public function passwordConfirmValidate($attribute)
    {
        if ($this->$attribute !== $this->password) {
            $this->addError($attribute, 'Подтверждение пароля не совпадает с паролем');
        }
    }

    /**
     * @return int|false
     * @throws \yii\base\Exception
     */
    public function registration()
    {
        if ($this->validate()) {
            $user = new User;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $user->activation_hash = Yii::$app->security->generateRandomString();
            $user->save();

            foreach (NotificationType::find()->all() as $item) {
                $user->link('notificationTypes', $item);
            }

            $user->on(User::EVENT_AFTER_CREATE, [$user, 'sendNotification'], [
                'code' => User::EVENT_AFTER_CREATE,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'params' => [
                    'sitename' => Yii::$app->id,
                    'username' => $user->username,
                    'password' => $this->password,
                    'linkToUser' => Html::a('Ссылка', Url::home(true) . 'admin/user/view?id=' . $user->id),
                    'linkActivation' => Html::a('Ссылка', Url::home(true) . 'registration/activation?hash=' . $user->activation_hash),
                ],
            ]);

            $user->trigger(User::EVENT_AFTER_CREATE);

            return $user->id;
        }

        return false;
    }

    /**
     * @param $hash string
     * @return User
     * @throws HttpException
     */
    public function activation($hash)
    {
        $user = User::findOne(['activation_hash' => $hash]);

        if (!$user) {
            throw new HttpException('500', 'User not found');
        }

        if (!array_key_exists('user', Yii::$app->authManager->getRolesByUser($user->id))) {
            $user->link('roles', AuthItem::findOne(['name' => 'user']));
        }

        $user->activation_hash = null;
        $user->save();

        return $user;
    }

}
