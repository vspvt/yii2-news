<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Login extends Model
{

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var bool */
    public $rememberMe = true;

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'passwordValidate'],
            ['username', 'rolesValidate'],
        ];
    }

    /**
     * @param string $attribute Пароль
     */
    public function passwordValidate($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->passwordValidate($this->password)) {
                $this->addError($attribute, 'Не верное имя пользователя или пароль.');
            }
        }
    }

    /**
     * @return null|User
     */
    public function getUser()
    {
        return User::findOne(['username' => $this->username]);
    }

    /**
     * @param string $attribute username
     */
    public function rolesValidate($attribute)
    {
        if (!$this->hasErrors()) {
            /** @var User $user */
            $user = $this->getUser();

            $userHasRoles = count(Yii::$app->authManager->getRolesByUser($user->id));

            if (!$userHasRoles) {
                $this->addError($attribute, 'Ваш аккаунт заблокирован или еще не прошел модерацию.');
            }
        }
    }

    /**
     * @param bool $autoLogin
     * @return bool
     */
    public function login($autoLogin = false)
    {
        if (!$autoLogin) {
            if ($this->validate()) {
                return $this->_login();
            }

            return false;
        }

        return $this->_login();
    }

    /**
     * @return bool
     */
    public function _login()
    {
        $user = $this->getUser();
        if ($result = Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0)) {
            $user->lastauth_at = date('Y-m-d H:i:s');
            $user->save();
        }

        return $result;
    }
}
