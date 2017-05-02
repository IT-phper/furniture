<?php

namespace app\models;

use app\components\Salt;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $real_name
 * @property string $salt
 * @property integer $role
 * @property integer $shop_id
 * @property string $authKey
 * @property string $accessToken
 * @property string $created
 * @property string $updated
 * @property integer $status
 * @property string $secret
 */
class User extends \yii\db\ActiveRecord
{
    //启用状态
    const USER_TABLE_STATUS_ACTIVE = 1;
    //暂停状态
    const USER_TABLE_STATUS_PAUSED = 2;
    //删除状态
    const USER_TABLE_STATUS_DELETE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['salt'], 'required'],
            [['role', 'shop_id', 'status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['username'], 'string', 'max' => 60],
            [['password', 'secret'], 'string', 'max' => 32],
            [['real_name'], 'string', 'max' => 20],
            [['salt'], 'string', 'max' => 6],
            [['authKey', 'accessToken'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '管理员邮箱',
            'password' => '密码',
            'real_name' => '真实姓名',
            'salt' => '随机盐值',
            'role' => '管理员角色',
            'shop_id' => '店铺ID',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
            'created' => '创建时间',
            'updated' => '更新时间',
            'status' => '状态',
            'secret' => 'Secret',
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new UserQuery(get_called_class()))->andWhere(['<>', self::tableName() . '.status', 3]);
    }

    /**
     * 关联管理员分组列表
     */
    public function getRole0()
    {
        return $this->hasOne(Role::className(), ['role' => 'role']);
    }

    /**
     * 关联shops表
     */
    public function getShops()
    {
        return $this->hasOne(Shops::className(), ['id' => 'shop_id']);
    }

    /**
     * 验证登录账号和密码
     */
    public static function validatePassword($username, $password)
    {
        $data = self::find()->where(['username' => $username, 'status' => self::USER_TABLE_STATUS_ACTIVE])->asArray()->one();
        if ($data) {
            //验证自己店铺是否处于正常营业
            if ($data['shop_id']) {
                $shop = Shops::findOne($data['shop_id']);
                if ($shop->status !== 1) return false;
            }

            if ($data['password'] === Salt::verifySalt($password, $data['salt'])) return true;
        }     
        return false;
    }

    /**
     * 通过real_name获取role
     *  管理员组列表中搜索名字
     */
    public static function getRoleByRealName($real_name) 
    {
        return self::find()->select('role')->where(['real_name' => $real_name])->asArray()->all();
    }
}
