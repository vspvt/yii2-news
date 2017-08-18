<?php

use yii\db\Migration;

class m170811_123817_create_notification_type_table extends Migration
{
    protected $_tableName = 'notification_type';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(128),
        ]);

        $this->batchInsert($this->_tableName, ['id', 'name'], [
            [1, 'email'],
            [2, 'web'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->_tableName);
    }

}
