<?php

use app\models\News;
use yii\db\Migration;

class m170813_112718_create_news_table extends Migration
{
    protected $_tableName = 'news';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->string(),
            'text' => $this->text(),
            'image' => $this->string(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
            'status' => $this->boolean()->defaultValue(0),
            'user_id' => $this->integer()->notNull(),
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

        for ($i = 1; $i <= 20; $i++) {
            $date = date('Y-m-d H:i:s');
            $this->insert($this->_tableName, [
                'id' => $i,
                'title' => sprintf('News-%d', $i),
                'description' => 'Description',
                'text' => 'Full text',
                'created_at' => $date,
                'updated_at' => $date,
                'user_id' => 2,
            ]);

            $news = News::findOne(['id' => $i]);
            $news->on(News::EVENT_AFTER_CREATE, [$news, 'sendNotification'], [
                'code' => News::EVENT_AFTER_CREATE,
            ]);
            $news->trigger(News::EVENT_AFTER_CREATE);
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}
