<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_product}}`.
 */
class m220427_143351_create_category_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_product}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull()->comment('id категория'),
            'product_id' => $this->integer()->notNull()->comment('id товара'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category_product}}');
    }
}
