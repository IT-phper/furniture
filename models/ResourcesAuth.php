<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resources_auth".
 *
 * @property integer $rid
 * @property integer $frid
 * @property integer $uid
 * @property string $controller
 * @property string $action
 * @property integer $status
 */
class ResourcesAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resources_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['frid', 'uid', 'status'], 'integer'],
            [['uid', 'controller', 'action'], 'required'],
            [['controller', 'action'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rid' => '主键',
            'frid' => '外键',
            'uid' => '用户角色ID',
            'controller' => '控制器名称',
            'action' => '动作名称',
            'status' => '授权状态',
        ];
    }
}
