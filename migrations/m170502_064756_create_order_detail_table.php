<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_detail`.
 */
class m170502_064756_create_order_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `order_detail` (
            `id` int(11) not null AUTO_INCREMENT COMMENT '主键',
            `frid` int(11) not null COMMENT '外键',
            `goods_id` int(11) not null COMMENT '商品id',
            `order_num` int(11) not null COMMENT '数量',
            `retail_price` int(11) not null COMMENT '单价',
            PRIMARY KEY(`id`)
        )ENGINE=Innodb default charset=utf8")->execute(); 
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_detail');
    }
}
