<?php

use yii\db\Migration;

/**
 * Handles the creation of table `orders`.
 */
class m170502_062931_create_orders_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `orders` (
            `id` int(11) not null AUTO_INCREMENT COMMENT '主键',
            `order_id` char(16) not null COMMENT '订单编号',
            `shop_id` int(11) not null COMMENT '店铺号',
            `user_id` int(11) not null COMMENT '处理人',
            `created` datetime COMMENT '创建时间',
            `total` int(11) COMMENT '总金额',
            PRIMARY KEY(`id`)
        )ENGINE=Innodb default charset=utf8")->execute(); 
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('orders');
    }
}
