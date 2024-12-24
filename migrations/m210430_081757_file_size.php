<?php

use yii\db\Migration;

/**
 * Class m210430_081757_file_size
 */
class m210430_081757_file_size extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('files', 'size', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210430_081757_file_size cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210430_081757_file_size cannot be reverted.\n";

        return false;
    }
    */
}