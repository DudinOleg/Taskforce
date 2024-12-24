<?php

use yii\db\Migration;

/**
 * Class m210423_152627_file_uid
 */
class m210423_152627_file_uid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'uid', $this->char(64)->unique());
        $this->addColumn('files', 'task_uid', $this->char(64));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210423_152627_file_uid cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210423_152627_file_uid cannot be reverted.\n";

        return false;
    }
    */
}