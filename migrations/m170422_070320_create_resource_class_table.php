<?php

use yii\db\Migration;

/**
 * Handles the creation of table `resource_class`.
 */
class m170422_070320_create_resource_class_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `resource_class` (
         `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
         `name` varchar(255) DEFAULT NULL COMMENT '资源类名',
         PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('resource_class');
    }
}
