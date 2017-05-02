<?php

use yii\db\Migration;

/**
 * Handles the creation of table `resources`.
 */
class m170422_065250_create_resources_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `resources` (
             `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
             `controller` varchar(255) NOT NULL COMMENT '控制器名称',
             `action` varchar(255) NOT NULL COMMENT '动作名称',
             `description` varchar(255) NOT NULL COMMENT '资源描述',
             `module_id` int(11) DEFAULT NULL,
             PRIMARY KEY (`rid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('resources');
    }
}
