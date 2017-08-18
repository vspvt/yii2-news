<?php

use yii\db\Migration;

class m170811_153917_create_notification_template_table extends Migration
{
    protected $_tableName = 'notification_template';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer(3),
            'title' => $this->string(128),
            'text' => $this->text(),
            'duty' => $this->boolean(),
        ]);
        $this->addForeignKey(
            Yii::$app->security->generateRandomString(12),
            $this->_tableName,
            'event_id',
            'event',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->batchInsert(
            $this->_tableName,
            ['id', 'event_id', 'title', 'text', 'duty'],
            [
                [1, 1, 'Добавлена новая статья на сайте {sitename}', '{username}, добавлена новая статья {title}. Чтобы прочитать полностью, перейдите по ссылке {link}', false],
                [2, 2, 'Ваш пароль изменен на сайте {sitename}', '{username}, Ваш пароль изменен администратором. Ваш новый пароль: {newPass}', false],
                [3, 3, 'Зарегистрировался новый пользователь на сайте {sitename}', 'Администратор, новый юзер {username}, прошел регистрацию. Посмотреть профиль - {linkToUser}', true],
                [4, 3, 'Вы успешно прошли регистрацию на сайте {sitename}', '{username}, Вы зарегистрированы в системе, ваш пароль: {password}. Осталось подтвердить email – для этого перейдите по ссылке {linkActivation} ', false],
                [5, 4, 'Ваш аккаунт заблокирован на сайте {sitename}', '{username}, Ваш аккаунт заблокирован администратором', false],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}
