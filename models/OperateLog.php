<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "operate_log".
 *
 * @property integer $id
 * @property integer $module
 * @property integer $doId
 * @property integer $doUser
 * @property integer $doShop
 * @property integer $type
 * @property string $log
 * @property string $ip
 * @property string $reason
 * @property string $created
 * @property integer $status
 */
class OperateLog extends \yii\db\ActiveRecord
{
    //操作类型
    const OPERATE_TYPE_APPEND = 1; //添加
    const OPERATE_TYPE_ALTER  = 2; //修改
    const OPERATE_TYPE_DELETE = 3; //删除
    const OPERATE_TYPE_ACTIVE = 4; //启用
    const OPERATE_TYPE_PAUSE  = 5; //暂停

    /**
     *  定义操作记录所属模块，根据需要自己增加
     *  module字段定义
     */
    public $arModule = [
        101 => '管理员与授权',
        102 => '总店商品信息',
        103 => '分店入库信息',
        104 => '分店综合管理',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operate_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module', 'doId', 'doUser', 'doShop', 'type', 'ip'], 'required'],
            [['module', 'doId', 'doUser', 'doShop', 'type', 'status'], 'integer'],
            [['log'], 'string'],
            [['created'], 'safe'],
            [['ip', 'reason'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'module' => '所属模块',
            'doId' => '所属记录ID',
            'doUser' => '操作人',
            'doShop' => '所属分店id',
            'type' => '操作类型',
            'log' => '操作详情',
            'ip' => '操作地IP, IP+归属地',
            'reason' => '备注',
            'created' => '操作时间',
            'status' => '状态，1成功 0失败',
        ];
    }

    /**
     * @inheritdoc
     * @return OperateLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OperateLogQuery(get_called_class());
    }

     /**
     * @file OperateLogModel.php
     * @synopsis 插入日志记录
     * @param  integer $module 所属模块
     * @param  integer $doId   所属记录ID
     * @param  integer $doUser 操作人
     * @param  integer $doShop 所属分店ID
     * @param  string    $ip     操作者IP
     * @param  integer $type 操作类型,1添加, 2修改, 3删除
     * @param  string   $log   详细操作日志
     * @param  string   $reason 操作原因
     */
    public static function insertLog($module, $doId, $doUser, $ip, $type, $log = '', $reason = '', $doShop = 0) {
        $model = new self();
        $model->module = $module;
        $model->doId = $doId;
        $model->doUser = $doUser;
        $model->doShop = $doShop;
        $model->type = $type;
        $model->ip = $ip;
        $model->log = $log;
        $model->reason = $reason;
        $model->created = date("Y-m-d H:i:s");
        if ($model->save()) {
            return $model->id;
        }
        return false;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'doUser']);
    }
}
