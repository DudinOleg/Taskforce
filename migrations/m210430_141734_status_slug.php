<?php

use yii\db\Migration;

/**
 * Class m210430_141734_status_slug
 */
class m210430_141734_status_slug extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('statuses', 'slug', $this->char(16));

        $this->update('statuses', ['slug' => 'new'], ['id' => 1]);
        $this->update('statuses', ['slug' => 'cancel'], ['id' => 2]);
        $this->update('statuses', ['slug' => 'proceed'], ['id' => 3]);
        $this->update('statuses', ['slug' => 'complete'], ['id' => 4]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210430_141734_status_slug cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210430_141734_status_slug cannot be reverted.\n";

        return false;
    }
    */
}