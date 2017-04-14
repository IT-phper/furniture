<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property integer $role
 * @property string $name
 * @property string $created
 * @property string $updated
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created', 'updated'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role' => '主键',
            'name' => '用户组名称',
            'created' => '创建时间',
            'updated' => '创建时间',
        ];
    }

    /**
     * 获取管理员组列表（id为索引）
     */
    public static function getRoleList()
    {
        return self::find()->select('name')->orderBy('role')->indexBy('role')->column();
    }

    /**
     * 关联User表
     */
    public function getUser()
    {
        return $this->hasMany(User::className(), ['role' => 'role']);
    }

    public static function find()
    {
        return (new RoleQuery(get_called_class()));
    } 

}


class RoleQuery extends \yii\db\ActiveQuery
{

    /**
     * @inheritdoc
     * @return Role[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Role|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function searchName($name = null)
    {
        return $this->andFilterWhere(['like', 'name', trim($name)]);
    }

    public function searchUsername($username = null)
    {
        if (!$username) return $this;
        $role = User::getRoleByRealName(trim($username));
        if (!role) return $this;
        return $this->andWhere(['in', 'role', $role]);
    }
}
