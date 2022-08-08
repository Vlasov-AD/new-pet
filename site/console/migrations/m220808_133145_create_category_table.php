<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m220808_133145_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'status' => $this->tinyInteger()->defaultValue(1),
            'title' => $this->string(255)->comment('Заголовок h1'),
            'lft' => $this->integer()->notNull()->comment('Позиция слева'),
            'rgt' => $this->integer()->notNull()->comment('Позиция справа'),
            'depth' => $this->integer()->notNull()->comment('Глубина'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'first_image' => $this->string(255)->comment('Основное фото'),
        ]);

        $this->insert('{{%category}}', [
            'name' => 'без категории',
            'slug' => 'default',
            'lft' => 1,
            'rgt' => 2,
            'depth' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }
}
