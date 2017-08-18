<?php

use yii\db\Migration;

class m170810_191321_create_user_table extends Migration
{
    protected $_tableName = 'user';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->text()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
            'lastauth_at' => $this->timestamp()->notNull(),
            'activation_hash' => $this->string(128)
        ]);

        $defaultPasswordHash = Yii::$app->security->generatePasswordHash('123456');
        $this->batchInsert(
            $this->_tableName,
            ['id', 'username', 'email', 'password_hash'],
            [
                [1, 'admin', 'admin@domain.com', $defaultPasswordHash],
                [2, 'moderator', 'moderator@domain.com', $defaultPasswordHash],
                [3, 'user', 'user@domain.com', $defaultPasswordHash],
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
