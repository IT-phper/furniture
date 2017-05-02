<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_detail".
 *
 * @property integer $id
 * @property integer $frid
 * @property integer $goods_id
 * @property integer $order_num
 * @property integer $retail_price
 */
class OrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['frid', 'goods_id', 'order_num', 'retail_price'], 'required'],
            [['frid', 'goods_id', 'order_num', 'retail_price'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'frid' => 'Frid',
            'goods_id' => 'Goods ID',
            'order_num' => 'Order Num',
            'retail_price' => 'Retail Price',
        ];
    }
}
