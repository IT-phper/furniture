<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use yii\data\Pagination;
use app\models\ResourcesAuth;


class BaseController extends Controller
{
	public $layout = 'main'; 

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->getResponse()->redirect('/login')->send();
            return;
        }
        
        // return parent::beforeAction($action);
        if (parent::beforeAction($action)) {
            if (!$this->verifyAuth()) {
                echo $this->render('/index/no_auth');
                exit();
            } else {
                return true;
            }
        }
        return false;
    }


    /**
     * 根据资源授权表验证当前用户是否授权
     */
    protected function verifyAuth()
    {

        $role_id = Yii::$app->user->identity->role;
        $controller_id = $this->id;
        $action_id = $this->action->id;

        // 超级管理员和总部管理员
        if ($role_id == 146 || $role_id == 147) {
            if (controller_id == 'branch') {
                return $this->render('/index/403');
            }
            return true;            
        }

        // 修改自己密码资源不验证
        if ($controller_id == 'auth' && $action_id == 'update_password') return true;
        if ($controller_id == 'index' && $action_id == 'error') return true;

        $res_auth_list = ResourcesAuth::find()
            ->where(['status' => '1', 'uid' => $role_id])
            ->all();

        foreach ($res_auth_list as $auth) {
            if ($auth['uid'] == $role_id &&
                $auth['controller'] == $controller_id &&
                $auth['action'] == $action_id
            ) {
                return true;
            }
        }
    }

   	/**
     * _page 对象进行分页
     * @param  object $object ActiveQuery Object
     * @param  integer $currentPage 当前页面
     * @param  integer $pageSize 每页显示数据条数
     * @param  boolean $type 返回数据类型，true返回对象集合数据，false返回数组集合
     * @return  array
     * @usage:
     *     $object = ClientGame::find()->where();
     *     $this->_page($object, $currentPage, $pageSize, $type);
     */
    public function _page($object, $currentPage = 1, $pageSize = 10, $type = true)
    {
        $pages = new Pagination(['totalCount' => $object->count(), 'pageSize' => $pageSize, 'page' => $currentPage - 1]);
        if ($type) {
            $data = $object->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        } else {
            $data = $object->offset($pages->offset)
                ->limit($pages->limit)
                ->asArray()
                ->all();
        }
        return [
            'data' => $data,
            'pages' => $pages,
        ];
    }
}

