<?php

use yii\db\Migration;

/**
 * Class m210416_133928_avatars
 */
class m210416_133928_avatars extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'avatar', $this->char(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210416_133928_avatars cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210416_133928_avatars cannot be reverted.\n";

        return false;
    }
    */
}