<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $picture
 * @property integer $num
 * @property integer $price
 * @property string $spec
 * @property string $intro
 * @property string $created
 * @property integer $status
 */
class Goods extends \yii\db\ActiveRecord
{

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'num', 'price'], 'required'],
            [['name'], 'unique'],
            [['num', 'price', 'status'], 'integer'],
            [['num', 'price'], 'compare', 'compareValue' => 0, 'operator' => '>'],
            [['created'], 'safe'],
            [['name'], 'string', 'max' => 60],
            [['spec', 'intro'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024*1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '商品ID号',
            'name' => '商品名',
            'file' => '图片',
            'num' => '库存(件)',
            'price' => '指导价(元)',
            'spec' => '规格',
            'intro' => '说明',
            'created' => '上架时间',
            'status' => '商品状态',
            'picture' => '图片',
        ];
    }

    public static function getNameFromId($id)
    {
        return self::findOne($id)->name;
    }
}
