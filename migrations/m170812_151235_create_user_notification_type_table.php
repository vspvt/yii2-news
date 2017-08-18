<?php

use yii\db\Migration;

class m170812_151235_create_user_notification_type_table extends Migration
{
    protected $_tableName = 'user_notification_type';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'user_id' => $this->integer(),
            'notification_type_id' => $this->integer()
        ]);
        $this->addPrimaryKey('pk' ,$this->_tableName, ['user_id', 'notification_type_id']);
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
        $this->batchInsert(
            $this->_tableName,
            ['user_id', 'notification_type_id'],
            [
                [1, 1],
                [2, 1],
                [3, 1],
                [3, 2],
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
