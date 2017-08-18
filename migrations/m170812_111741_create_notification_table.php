<?php

use yii\db\Migration;

class m170812_111741_create_notification_table extends Migration
{
    protected $_tableName = 'notification';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string(128),
            'text' => $this->text(),
            'user_id' => $this->integer(6),
            'notification_type_id' => $this->integer(3),
            'read' => $this->integer(1),
        ]);
        $this->addForeignKey(
            Yii::$app->security->generateRandomString(12),
            $this->_tableName,
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            Yii::$app->security->generateRandomString(12),
            $this->_tableName,
            'notification_type_id',
            'notification_type',
            'id',
            'CASCADE',
            'CASCADE'
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
