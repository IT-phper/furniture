<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shops".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $addr
 * @property string $email
 * @property integer $status
 * @property integer $leader
 * @property string $created
 */
class Shops extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shops';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'leader'], 'integer'],
            [['created'], 'required'],
            [['created'], 'safe'],
            [['name'], 'string', 'max' => 60],
            [['phone'], 'string', 'max' => 30],
            [['addr', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'addr' => 'Addr',
            'email' => 'Email',
            'status' => 'Status',
            'leader' => 'Leader',
            'created' => 'Created',
        ];
    }

    /**
     * @inheritdoc
     * @return ShopsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new ShopsQuery(get_called_class()))->andWhere(['<>', self::tableName() . '.status', 3]);
    }

    public static function getShopsList()
    {
        return self::find()->select(['name', 'id'])->orderBy('id')->indexBy('id')->column();
    }

    public function getUser()
    {
        return $this->hasMany(User::className(), ['shop_id' => 'id']);
    }

}
