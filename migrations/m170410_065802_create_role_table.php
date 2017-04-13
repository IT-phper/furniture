<?php

use yii\db\Migration;

/**
 * Handles the creation of table `role`.
 */
class m170410_065802_create_role_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `role` (
            `role` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
            `name` varchar(50) DEFAULT '' COMMENT '管理员角色',
            `created` datetime DEFAULT NULL COMMENT '创建时间',
            `updated` datetime DEFAULT NULL COMMENT '修改时间',
            PRIMARY KEY (`role`)
            ) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('role');
    }
}
