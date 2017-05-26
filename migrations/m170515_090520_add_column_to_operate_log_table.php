<?php

use yii\db\Migration;

class m170515_090520_add_column_to_operate_log_table extends Migration
{
    public function up()
    {
        $this->addColumn('operate_log', 'doShop', $this->integer()->after('doUser')->notNull()->defaultValue(0)->comment('所属分店id'));
    }

    public function down()
    {
        $this->dropColumn('operate_log', 'doShop');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
