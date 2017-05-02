<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "s_goods".
 *
 * @property integer $id
 * @property integer $fid
 * @property integer $shop_id
 * @property string $created
 * @property integer $sale_price
 * @property integer $sale_num
 */
class SGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 's_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fid', 'shop_id'], 'required'],
            [['fid', 'shop_id', 'sale_price', 'sale_num'], 'integer'],
            [['created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'fid' => '外键',
            'shop_id' => '商品ID',
            'created' => '上架时间',
            'sale_price' => '销售价格',
            'sale_num' => '库存',
        ];
    }

    /**
     * @inheritdoc
     * @return SGoodsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SGoodsQuery(get_called_class());
    }

    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'fid']);
    }
}
