<?php

use yii\db\Migration;

/**
 * Class m210502_044601_user_fields
 */
class m210502_044601_user_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'bd_date', $this->date());
        $this->addColumn('users', 'phone', $this->char(16));
        $this->addColumn('users', 'tg', $this->char(255));
        $this->addColumn('users', 'description', $this->text());
        $this->addColumn('users', 'hide_contacts', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210502_044601_user_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210502_044601_user_fields cannot be reverted.\n";

        return false;
    }
    */
}