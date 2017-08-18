<?php

use app\models\News;
use app\models\User;
use yii\db\Migration;

class m170811_111721_create_event_table extends Migration
{
    protected $_tableName = 'event';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(3),
            'code' => $this->string(128),
            'name' => $this->string(128),
            'params' => $this->string(128),
        ]);
        $this->createIndex(
            Yii::$app->security->generateRandomString(12),
            $this->_tableName,
            'code',
            true
        );
        $this->batchInsert(
            $this->_tableName,
            ['id', 'code', 'name', 'params'],
            [
                [1, News::EVENT_AFTER_CREATE, 'Новость: после создания', 'params' => '{sitename}, {username}, {title}, {link}'],
                [2, User::EVENT_AFTER_PASSWORD_CHANGE, 'Пользователь: после изменения пароля', 'params' => '{sitename}, {username}, {newPass}'],
                [3, User::EVENT_AFTER_CREATE, 'Пользователь: после создания', 'params' => '{sitename}, {username}, {password}, {linkActivation}, {linkToUser}'],
                [4, User::EVENT_AFTER_BLOCK, 'Пользователь: после блокирования', 'params' => '{sitename}, {username}'],
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
