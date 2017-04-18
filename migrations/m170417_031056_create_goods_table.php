<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170417_031056_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `goods` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
            `name` varchar(60) NOT NULL COMMENT '商品名',
            `picture` varchar(255) COMMENT '图片',
            `num` int(11) NOT NULL DEFAULT 0 COMMENT '库存',
            `price` int(11) NOT NULL DEFAULT 0 COMMENT '价格',
            `spec` varchar(255) COMMENT '规则',
            `intro` varchar(255) COMMENT '说明',
            `created` DATETIME NOT NULL COMMENT '上架时间', 
            `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1启用 2下架 3删除',
            PRIMARY KEY(`id`)
            ) ENGINE=Innodb default charset=utf8")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
