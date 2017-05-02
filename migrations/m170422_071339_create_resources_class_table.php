<?php

use yii\db\Migration;

/**
 * Handles the creation of table `resources_class`.
 */
class m170422_071339_create_resources_class_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `resources_auth` (
         `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
         `frid` int(12) DEFAULT NULL COMMENT '外键',
         `uid` int(11) unsigned NOT NULL COMMENT '用户角色ID',
         `controller` varchar(255) NOT NULL COMMENT '控制器名称',
         `action` varchar(255) NOT NULL COMMENT '动作名称',
         `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '授权状态',
         PRIMARY KEY (`rid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('resources_class');
    }
}
