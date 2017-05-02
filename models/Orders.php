<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property string $order_id
 * @property integer $shop_id
 * @property integer $user_id
 * @property string $created
 * @property integer $total
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'shop_id', 'user_id'], 'required'],
            [['shop_id', 'user_id', 'total'], 'integer'],
            [['created'], 'safe'],
            [['order_id'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'shop_id' => 'Shop ID',
            'user_id' => 'User ID',
            'created' => 'Created',
            'total' => 'Total',
        ];
    }

    /**
     * @inheritdoc
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrdersQuery(get_called_class());
    }
}
