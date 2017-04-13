<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170405_061448_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `user` ( 
        `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户主键',
        `username` varchar(60) NOT NULL DEFAULT '' COMMENT '管理员名称',
        `password` varchar(32) NOT NULL DEFAULT '' COMMENT '管理员密码',
        `real_name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
        `salt` varchar(6) NOT NULL COMMENT '随机盐值',
        `role` int(11) NOT NULL DEFAULT 0 COMMENT '管理员角色',
        `shop_id` int(11) DEFAULT 0 COMMENT '店铺ID',
        `authKey` varchar(255) DEFAULT NULL COMMENT '验证Key',
        `accessToken` varchar(255) DEFAULT NULL COMMENT '验证Token',
        `created` datetime DEFAULT NULL COMMENT '创建时间',
        `updated` datetime DEFAULT NULL COMMENT '更改时间', 
        `status` int(11) DEFAULT NULL COMMENT '启用状态' ,
        `secret` varchar(32) DEFAULT NULL COMMENT '谷歌二次验证密钥',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
