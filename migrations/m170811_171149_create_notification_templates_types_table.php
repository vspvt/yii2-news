<?php

use yii\db\Migration;

class m170811_171149_create_notification_templates_types_table extends Migration
{
    protected $_tableName = 'notification_templates_types';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'notification_template_id' => $this->integer(),
            'notification_type_id' => $this->integer(),
        ]);
        $this->addPrimaryKey('notification_template_id', $this->_tableName, ['notification_template_id', 'notification_type_id']);
        $this->addForeignKey(Yii::$app->security->generateRandomString(12), $this->_tableName, 'notification_template_id', 'notification_template', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey(Yii::$app->security->generateRandomString(12), $this->_tableName, 'notification_type_id', 'notification_type', 'id', 'CASCADE', 'CASCADE');
        $this->batchInsert(
            $this->_tableName,
            ['notification_template_id', 'notification_type_id'],
            [
                [1, 2],
                [2, 1],
                [3, 1],
                [4, 1],
                [5, 1],
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
