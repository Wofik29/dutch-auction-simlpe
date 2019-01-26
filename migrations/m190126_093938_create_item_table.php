<?php

use yii\db\Migration;

/**
 * Handles the creation of table `item`.
 */
class m190126_093938_create_item_table extends Migration
{
    const TABLE_NAME = 'item';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'seller_id' => $this->integer()->notNull(),
            'buyer_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'desc' => $this->text(),
            'start_price' => $this->decimal(10,2)->notNull(),
            'end_price' => $this->decimal(10,2)->notNull(),
            'sell_price' => $this->decimal(10,2)->notNull(),
            'step_price'  => $this->decimal(10,2)->notNull(),
            'step_time' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(1),
        ]);

        $this->createIndex('idx-seller-status', self::TABLE_NAME, 'seller_id');
        $this->createIndex('idx-buyer-status', self::TABLE_NAME, 'buyer_id');
        $this->createIndex('idx-item-status', self::TABLE_NAME, 'status');

        $this->addForeignKey('fk-item-seller', self::TABLE_NAME, 'seller_id', 'user', 'id');
        $this->addForeignKey('fk-item-buyer', self::TABLE_NAME, 'buyer_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
