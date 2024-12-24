<?php

use yii\db\Migration;

/**
 * Class m210415_081036_add_cats
 */
class m210415_081036_add_cats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('statuses', ['name'], [['Новое'],['Отменено'],['В работе'],['Выполнено'],['Провалено']]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210415_081036_add_cats cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210415_081036_add_cats cannot be reverted.\n";

        return false;
    }
    */
}