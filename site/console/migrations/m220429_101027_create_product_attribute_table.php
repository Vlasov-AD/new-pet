<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_attribute}}`.
 */
class m220429_101027_create_product_attribute_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_attribute}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull()->comment('id товара'),
            'attribute_id' => $this->integer()->notNull()->comment('id характеристики'),
            'sort' => $this->integer()->unsigned(),
            'value' => $this->string()->comment('значение')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product_attribute}}');
    }
}
