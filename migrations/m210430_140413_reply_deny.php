<?php

use yii\db\Migration;

/**
 * Class m210430_140413_reply_deny
 */
class m210430_140413_reply_deny extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('replies', 'is_denied', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210430_140413_reply_deny cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210430_140413_reply_deny cannot be reverted.\n";

        return false;
    }
    */
}