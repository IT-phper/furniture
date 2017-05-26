<?php 
	
namespace app\controllers;

use Yii;
use app\models\Role;
use app\models\User;
use app\models\Resources;
use app\models\ResourceClass;
use app\models\ResourcesAuth;
use app\components\Salt;
use app\models\OperateLog;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class AuthController extends BaseController
{

	/**
	 * 管理员列表
	 */
	public function actionIndex()
	{
		$user = new user();	

		//添加管理员
		if ($post_data = Yii::$app->request->post()) {
			$data = $post_data['AdminUsers'];
			$info = Salt::generateSalt($data['password']);

			//赋值
			$user->username = $data['username'];
			$user->shop_id = $data['shop_id'];
			$user->role = $data['role'];
			$user->real_name = $data['real_name'];
			$user->status = User::USER_TABLE_STATUS_ACTIVE;
            $user->created = date('Y-m-d H:i:s');
            $user->password = $info['hash'];
            $user->salt = $info['salt'];

            if ($user->save()){
            	//记录日志
            	OperateLog::insertLog(101, 
            		$user->id,
            		Yii::$app->user->id,
            		Yii::$app->request->getUserIP(),
            		OperateLog::OPERATE_TYPE_APPEND,
            		'添加管理员' . $user->real_name,
            		'添加管理员'
            		);
            	Yii::$app->session->setFlash('success', '新建管理员成功');
            	return $this->redirect(['/auth/index']);
            }
			Yii::$app->session->setFlash('error', '新建管理员失败');
			return $this->redirect(['/auth/index']);
		}

		//查询
		$queryParams = Yii::$app->request->queryParams;
		$pageSize = $queryParams['per-page'] ? $queryParams['per-page'] : 5;
		$model = $user::find()
			->searchUsername($queryParams['username'])
			->searchRealname($queryParams['real_name'])
			->searchRole($queryParams['role'])
			->searchStatus($queryParams['status'])
			->searchShop_id($queryParams['shop_id']);


		//分页
 		$pagination = new Pagination(['totalCount' => $model->count(), 'pageSize' => $pageSize]);
		$users = $model->offset($pagination->offset)
    		->limit($pagination->limit)
    		->all();
		return $this->render('index', [
			'users' => $users,
			'pagination' => $pagination,
		]);
	}

    /**
     * 分店管理员列表
     */
    public function actionShop()
    {
        $shop_id = Yii::$app->user->identity->shop_id;

        //查询
        $queryParams = Yii::$app->request->queryParams;
        $pageSize = $queryParams['per-page'] ? $queryParams['per-page'] : 5;
        $model = User::find()
            ->searchUsername($queryParams['username'])
            ->searchRealname($queryParams['real_name'])
            ->searchRole('149')
            ->searchStatus($queryParams['status'])
            ->searchShop_id($shop_id);


        //分页
        $pagination = new Pagination(['totalCount' => $model->count(), 'pageSize' => $pageSize]);
        $users = $model->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('shop', [
            'users' => $users,
            'pagination' => $pagination,
        ]);

    }

    /**
     * 新增分店管理员
     */
    public function actionNew_shop_user()
    {
        $shop_id = Yii::$app->user->identity->shop_id;

        $user = new user(); 

        //添加管理员
        if ($post_data = Yii::$app->request->post()) {
            $data = $post_data['AdminUsers'];
            $info = Salt::generateSalt($data['password']);

            //赋值
            $user->username = $data['username'];
            $user->shop_id = $shop_id;
            $user->role = 149;
            $user->real_name = $data['real_name'];
            $user->status = User::USER_TABLE_STATUS_ACTIVE;
            $user->created = date('Y-m-d H:i:s');
            $user->password = $info['hash'];
            $user->salt = $info['salt'];

            if ($user->save()){
                //记录日志
                OperateLog::insertLog(101, 
                    $user->id,
                    Yii::$app->user->id,
                    Yii::$app->request->getUserIP(),
                    OperateLog::OPERATE_TYPE_APPEND,
                    '添加管理员' . $user->real_name,
                    '添加管理员'
                    );
                Yii::$app->session->setFlash('success', '新建管理员成功');
                return $this->redirect(['/auth/shop']);
            }
            Yii::$app->session->setFlash('error', '新建管理员失败');
            return $this->redirect(['/auth/shop']);
        }
    }

	/**
	 *管理员编辑
	 */
	public function actionAdmin_change_role()
	{
		if ($post_data = Yii::$app->request->post()) {
			$user = User::findOne($post_data['id']);
			$user->role = $post_data['role'];
			$user->shop_id = $post_data['shop_id'];
			if ($user->save()) {
				OperateLog::insertLog(101, 
            		$user->id,
            		Yii::$app->user->id,
            		Yii::$app->request->getUserIP(),
            		OperateLog::OPERATE_TYPE_ALTER,
            		'将' . $user->real_name . '设置为' . $user->role0->name,
            		'编辑管理员'
            		);
				Yii::$app->session->setFlash('success', '编辑管理员成功');
				return $this->redirect(['/auth/index']);
			} 
		}
		Yii::$app->session->setFlash('error', '修改失败');
		return $this->redirect(['/auth/index']);
	}

	/**
	 * 修改管理员状态 ajax调用
	 */
	public function actionAdmin_change_status()
	{
		if ($data = Yii::$app->request->post()) {
			$do_id = Yii::$app->user->id;
            $ip	= Yii::$app->request->getUserIP();

            $user = User::findOne($data['id']);
            switch ($data['eventType']) {
                case '3':
                    $user->status = 3;
                    $user->update();
                    OperateLog::insertLog('101',
                        $user->id, $do_id, $ip,
                        3, "删除管理员 $user->real_name", $data['reason']);
                    Yii::$app->session->setFlash('success', '管理员已删除');
                    break;
                case '2':
                    $user->status = 1;
                    $user->update();
                    OperateLog::insertLog('101',
                        $user->id, $do_id, $ip,
                        4, "启用管理员 $user->real_name", $data['reason']);
                   	Yii::$app->session->setFlash('success', '管理员已启用');
                    break;
                case '1':
                    $user->status = 2;
                    $user->update();
                    OperateLog::insertLog('101',
                        $user->id, $do_id, $ip,
                        5, "停用管理员 $user->real_name", $data['reason']);
                  	Yii::$app->session->setFlash('success', '管理员已停用');
                    break;
            }
		}
	}

	/**
	 *修改密码 ajax调用 
	 */
	public function actionAdmin_change_password()
	{
		if ($data = Yii::$app->request->post()) {
            $id = $data['id'];
            $passwd = $data['pwd'];
            $password_confirm = $data['confirmPwd'];
            if ($passwd != $password_confirm) {
                Yii::$app->session->setFlash('error', '密码不一致');
                die;
            } 

            $salt_hash = Salt::generateSalt($passwd);
            $user = User::findOne($id);
            $user->password = $salt_hash['hash'];
            $user->salt = $salt_hash['salt'];
            $user->save();
            $do_id = Yii::$app->User->id;
            $ip = Yii::$app->request->getUserIP();
            OperateLog::insertLog('101',
                $user->id, $do_id, $ip,
                3, "$user->real_name 修改密码", '修改密码');
            Yii::$app->session->setFlash('success', '密码已更新');
        }
	}
	


	/**
	 * 管理员组
	 */
	public function actionRole()
	{
		$role = new role();

		//新建管理员组
		if ($post_data = Yii::$app->request->post()) {
			if ($role->load($post_data)) {
				$role->created = $role->updated = date('Y-m-d H:i:s');
				if ($role->save()) {
					OperateLog::insertLog('101',
						$role->role,
						Yii::$app->user->id,
						Yii::$app->request->getUserIP(),
						OperateLog::OPERATE_TYPE_APPEND,
						'新建管理员组 ' . $role->name,
						'新建管理员组'
					);
					Yii::$app->session->setFlash('success', '管理员组新建成功');
					return $this->redirect(['/auth/role']);
				}
			}
		}

		//查询
		$queryParams = Yii::$app->request->queryParams;
		$pageSize = $queryParams['per-page'] ? $queryParams['per-page'] : 5;
		$model = $role::find()
			->searchName($queryParams['name'])
			->searchUsername($queryParams['username']);
		//分页
 		$pagination = new Pagination(['totalCount' => $model->count(), 'pageSize' => $pageSize]);
		$data = $model->offset($pagination->offset)
    		->limit($pagination->limit)
    		->all();

		return $this->render('role', [
			'data' => $data,
			'pagination' => $pagination
		]);
	}

	/**
	 * 管理员组编辑
	 */
	public function actionUpdate_role()
	{
		if ($data = Yii::$app->request->post()) {
			$role = Role::findOne($data['id']);
			$role->name = $data['name'];
			$role->updated = date('Y-m-d H:i:s');
			if ($role->save()) {
				OperateLog::insertLog('101',
						$role->role,
						Yii::$app->user->id,
						Yii::$app->request->getUserIP(),
						OperateLog::OPERATE_TYPE_ALTER,
						'编辑管理员组 ' . $role->name,
						'编辑管理员组'
					);
				Yii::$app->session->setFlash('success', '修改成功');
				return $this->redirect(['/auth/role']);
			}
		}
		Yii::$app->session->setFlash('error', '非法操作');
		return $this->redirect(['/auth/role']);
	}

	/**
	 * 管理员组删除
	 */
	public function actionDelete_role()
	{
		if ($post_data = Yii::$app->request->post()) {

			if (!$post_data['reason']) {
				Yii::$app->session->setFlash('error', '备注信息不能为空');
				return $this->redirect(['/auth/role']);
			}

			$id = $post_data['id'];
			$res = User::find()->where([
            'role' => $id,
            ])->count();

			if ($res) {
				Yii::$app->session->setFlash('error', '需先清空改组成员');
				return $this->redirect(['/auth/role']);
			}

			$role = Role::findOne($id);
			if ($role->delete()) {
				OperateLog::insertLog('101',
					$role->role,
					Yii::$app->user->id,
					Yii::$app->request->getUserIP(),
					OperateLog::OPERATE_TYPE_DELETE,
					'删除管理员组' . $role->name,
					$post_data['reason']
				);
				Yii::$app->session->setFlash('success', '删除成功');
				return $this->redirect(['/auth/role']);
			}
		}
	}

	/**
	 * 修改密码
	 */
	public function actionUpdate_password()
	{
        if ($post_data = Yii::$app->request->post()) {
            if ($post_data['AdminUsers']['password'] !=
                $post_data['AdminUsers']['password_confirm']
            ) {
                Yii::$app->session->setFlash('error', '确认密码不一致');
                return $this->redirect(['/auth/update_password']);
            }

            unset($post_data['AdminUsers']['password_confirm']);

            $uid = Yii::$app->User->id;

            $user = User::findOne($uid);
            $salt_hash = Salt::generateSalt($post_data['AdminUsers']['password']);
            $user->password = $salt_hash['hash'];
            $user->salt = $salt_hash['salt'];

            if ($user->save()) {
                Yii::$app->session->setFlash('success', '密码修改成功');
                return $this->redirect(['/auth/update_password']);
            }
            Yii::$app->session->setFlash('error', '密码修改失败');
            return $this->redirect(['/auth/update_password']);
        }
        return $this->render('update_passwd.php');		
	}


	/**
     * 资源列表
     */
    public function actionRes()
    {
        $model = new Resources;

        $data = Yii::$app->request->post();

        if (!empty($data['uid'])) {
            $res = Resources::findOne($data['uid']);
            unset($data['uid']);
            $res->load($data);
            $res->update();
            $this->_syncRoleAuth_edit($res);
            // $this->_setSuccessFlash('资源修改成功');
            return $this->redirect('/auth/res');
        }

        $res = Resources::find()->where([
            'controller' => $data['Resources']['controller'],
            'action' => $data['Resources']['action'],
        ])->count();

        if ($res != '0') {
            // $this->_setErrorFlash('创建失败 请检查是否存在重复');
            return $this->redirect(['/auth/res']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->_syncRoleAuth($model);
                // $this->_setSuccessFlash('资源创建成功');
            } else {
                // $this->_setErrorFlash('创建失败 请检查日志');
            }
            return $this->redirect(['/auth/res']);
        }

        $query_params = Yii::$app->request->queryParams;
        $currentPage = $query_params['page'] ? $query_params['page'] : 1;
        $pageSize = $query_params['per-page'] ? $query_params['per-page'] : 10;

        $resources = Resources::find()
            ->searchId($query_params['id'])
            ->searchController($query_params['controller'])
            ->searchAction($query_params['action'])
            ->searchDescripiton($query_params['description'])
            ->searchModule($query_params['module'])
            ->orderBy('rid DESC');
        $pageInfo = $this->_page($resources, $currentPage, $pageSize);

        $resource_class = ResourceClass::find()->all();
        return $this->render('res_list.twig', [
            'resource_class' => ArrayHelper::map($resource_class, 'id', 'name'),
            'resources' => $pageInfo['data'],
            'pages' => $pageInfo['pages'],
        ]);
    }

    /**
     * 同步新资源到授权表所有用户组
     */
    private function _syncRoleAuth($model)
    {
        foreach (Role::find()->all() as $role) {
            $auth = new ResourcesAuth;
            $auth->frid = $model->rid;
            $auth->uid = $role->role;
            $auth->controller = $model->controller;
            $auth->action = $model->action;
            $auth->status = 0;
            $auth->save();
        }
    }

    /**
     * 删除资源
     */
    public function actionRes_delete()
    {
        $data = Yii::$app->request->post();
        Resources::findOne($data['id'])->delete();

        // $operate_log = new OperateLogModel;
        // $do_id = \Yii::$app->adminUser->id;
        // $ip = \Yii::$app->request->getUserIP();
        // $operate_log->insertLog('112',
        //     $user->uid, $do_id, $ip,
        //     3, "删除资源ID $data[id]", $data['reason']);
        // $this->_setErrorFlash('资源已删除');
    }

    /**
     * 同步新资源到授权表所有用户组 编辑资源时
     */
    private function _syncRoleAuth_edit($model)
    {
            $auth = ResourcesAuth::findAll([
                'frid' => $model->rid,
                ]);
            foreach ($auth as $key => $value) {
                $res = ResourcesAuth::findOne($value->rid);
                $res->controller = $model->controller;
                $res->action = $model->action;
                $res->update();
            }
        
    }

	/**
     * 批量权限分配
     */
    public function actionBatch_auth()
    {

        $grant_res_list = Yii::$app->request->post()['result'];

        if ($grant_res_list) {
            ResourcesAuth::updateAll(['status' => 0]);
            foreach ($grant_res_list as $uid => $res_list) {
                foreach ($res_list as $rid) {
                    ResourcesAuth::updateAll(
                        ['status' => 1], "uid = $uid and frid = $rid");
                }
            }
            // $this->_setSuccessFlash('批量授权修改成功');
            return $this->redirect(['/auth/batch_auth']);
        }

        $roles = Role::find()->all();
        $resources = Resources::find()->all();
        $res_class = ResourceClass::find()->all();

        $resource_module = [];
        foreach ($resources as $key => $value) {
            $resource_module[][$value['module_id']] = $value;
        }

        return $this->render('batch_auth.twig', [
            'roles' => $roles,
            'resources' => $resources,
            'resource_class' => ArrayHelper::map($res_class, 'id', 'name'),
            'resource_module' => ArrayHelper::toArray($resource_module),
        ]);
    }

    /**
     * 返回批量授权中已存在权限列表
     */
    public function actionHas_been_auth()
    {
        \Yii::$app->response->format = 'json';
        $role_res = [];

        $roles = Role::find()->all();
        $resource_auth = ArrayHelper::toArray(
            ResourcesAuth::findAll(['status' => 1]));

        foreach ($roles as $role) {
            foreach ($resource_auth as $k => $v) {
                if ($v['uid'] == $role->role) {
                    $role_res[$role->role][] = $v['frid'];
                }
            }
        }
        return $role_res;
    }

    /**
     * 模块添加
     */
    public function actionModule()
    {
        if ($post_data = Yii::$app->request->post()) {
            $ResourceClass = new ResourceClass();
            $ResourceClass->name = $post_data['name'];
            if ($ResourceClass->save()) {
                Yii::$app->session->setFlash('success', '模块已添加');
                return $this->redirect(['/auth/res']);
            }
        }
    }


    /**
     * 操作日志
     */
    public function actionDolog()
    {
        $query_params = Yii::$app->request->queryParams;
        // list($query_params['start'], $query_params['end']) = explode('--', $query_params['time']);
        $currentPage = $query_params['page'] ? $query_params['page'] : 1;
        $pageSize = $query_params['per-page'] ? $query_params['per-page'] : 5;

        $dologs = OperateLog::find()
            ->innerJoinWith('user')
            ->where(['module' => 101])
            ->orderBy('created DESC')
            ->searchDoUser($query_params['doUser'])
            ->searchIp($query_params['ip'])
            // ->searchType($query_params['type'])
            ->searchLog($query_params['log'])
            ->searchReason($query_params['reason']);
            // ->searchAll($query_params['search']);

        $pageInfo = $this->_page($dologs, $currentPage, $pageSize);

        return $this->render('dolog.twig', [
            'dologs' => $pageInfo['data'],
            'pages' => $pageInfo['pages'],
        ]);
    }

} 
