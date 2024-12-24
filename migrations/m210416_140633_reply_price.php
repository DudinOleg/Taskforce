<?php

use yii\db\Migration;

/**
 * Class m210416_140633_reply_price
 */
class m210416_140633_reply_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('replies', 'budget', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210416_140633_reply_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210416_140633_reply_price cannot be reverted.\n";

        return false;
    }
    */
}