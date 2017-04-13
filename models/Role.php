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
}
