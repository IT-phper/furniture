<?php

use yii\db\Migration;

/**
 * Handles the creation of table `operate_log`.
 */
class m170412_035416_create_operate_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `operate_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
            `module` smallint(6) NOT NULL COMMENT '所属模块',
            `doId` int(11) NOT NULL COMMENT '所属记录ID',
            `doUser` int(11) NOT NULL COMMENT '操作人',
            `type` tinyint(1) NOT NULL COMMENT '操作类型',
            `log` text COMMENT '操作详情',
            `ip` varchar(255) NOT NULL COMMENT '操作地IP, IP+归属地',
            `reason` varchar(255) DEFAULT NULL COMMENT '备注',
            `created` datetime DEFAULT NULL COMMENT '操作时间',
            `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，1成功 0失败',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('operate_log');
    }
}
