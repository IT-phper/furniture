<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shops`.
 */
class m170419_020221_create_shops_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `shops` (
            `id` int(11) not null AUTO_INCREMENT COMMENT '主键',
            `name` varchar(60) not null DEFAULT '' COMMENT '分店名称',
            `phone` varchar(30) not null DEFAULT '' COMMENT '分店电话',
            `addr` varchar(255) not null DEFAULT '' COMMENT '分店地址',
            `email` varchar(255) not null DEFAULT '' COMMENT '分店邮件',
            `status` tinyint(1) not null DEFAULT 1 COMMENT '1代表正常营业 2暂停营业 3删除',
            `leader` int(11) COMMENT '分店负责人',
            `created` DATETIME NOT NULL COMMENT '开店时间', 
            PRIMARY KEY(`id`)
        ) ENGINE=Innodb DEFAULT CHARSET=utf8")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('shops');
    }
}
