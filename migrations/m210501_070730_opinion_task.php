<?php

use yii\db\Migration;

/**
 * Class m210501_070730_opinion_task
 */
class m210501_070730_opinion_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('opinions', 'task_id', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210501_070730_opinion_task cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210501_070730_opinion_task cannot be reverted.\n";

        return false;
    }
    */
}