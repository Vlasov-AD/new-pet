<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m220427_141352_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(155)->unique()->notNull(),
            'slug' => $this->string(155)->unique()->notNull(),
            'price_current' => $this->integer()->notNull()->comment('Текущая цена'),
            'price_old' => $this->integer()->comment('Старая цена'),
            'available' => $this->tinyInteger(1)->defaultValue(1)->comment('Наличие'),
            'status' => $this->tinyInteger(1)->defaultValue(1)->comment('Статус'),
            'main_category' => $this->integer()->notNull()->comment('Главная категория'),
            'description' => $this->text()->comment('Описание'),
            'sort' => $this->integer()->unsigned()->comment('Сортировка товаров'),
            'first_image' => $this->string(255)->comment('Основное фото'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
