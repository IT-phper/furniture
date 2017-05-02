<?php

use yii\db\Migration;

/**
 * Handles the creation of table `s_goods`.
 */
class m170422_030027_create_s_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->db->createCommand("CREATE TABLE `s_goods` (
            `id` int(11) not null AUTO_INCREMENT COMMENT '主键',
            `fid` int(11) not null COMMENT '总部商品ID',
            `shop_id` int(11) not null COMMENT '分店ID',
            `created` datetime COMMENT ' 入店时间',
            `sale_price` int(11) COMMENT '销售价',
            `sale_num` int(11) COMMENT '本店库存',
            PRIMARY KEY(`id`)
        ) ENGINE=Innodb DEFAULT CHARSET=utf8")->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('s_goods');
    }
}
