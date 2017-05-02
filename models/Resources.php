<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resources".
 *
 * @property integer $rid
 * @property string $controller
 * @property string $action
 * @property string $description
 */
class Resources extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resources';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller', 'action', 'description'], 'required'],
            [['controller', 'action', 'description', 'module_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rid' => '主键',
            'controller' => '控制器名称',
            'action' => '动作名称',
            'description' => '资源描述',
            'module_id' => '模块ID',
        ];
    }

    /**
     * 重写find()返回自定义Query实例
     */
    public static function find()
    {
        return new ResourcesQuery(get_called_class());
    }
}


class ResourcesQuery extends \yii\db\ActiveQuery
{
    public function searchId($id = null) {
        if (!$id) return $this;
        return $this->andWhere(['rid' => trim($id)]);
    }

    public function searchController($controller = null) {
        if (!$controller) return $this;
        return $this->andFilterWhere(['like', 'controller', trim($controller)]);
    }

    public function searchAction($action = null) {
        if (!$action) return $this;
        return $this->andFilterWhere(['like', 'action', trim($action)]);
    }

    public function searchDescripiton($description = null) {
        if (!$description) return $this;
        return $this->andFilterWhere(['like', 'description', trim($description)]);
    }

    public function searchModule($module = null) {
        if (!$module) return $this;
        return $this->andFilterWhere(['module_id' => trim($module)]);
    }
}
