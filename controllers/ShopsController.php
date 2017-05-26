<?php 

namespace app\controllers;

use Yii;
use app\models\Shops;
use yii\data\Pagination;
use app\models\OperateLog;

class ShopsController extends BaseController
{
	public function actionIndex()
	{	
		//开店
		if ($post_data = Yii::$app->request->post()) {
			$model = new Shops();
			$model->created = date('Y-m-d H:i:s');
			if ($model->load($post_data['Shops'], '') && $model->save()) {
				OperateLog::insertLog(104, 
            		$model->id,
            		Yii::$app->user->id,
            		Yii::$app->request->getUserIP(),
            		OperateLog::OPERATE_TYPE_APPEND,
            		'添加分店：' . $model->name
            		);
				Yii::$app->session->setFlash('success', '添加分店成功');
	         	return $this->redirect(['/shops/index']);
			}
			Yii::$app->session->setFlash('error', '添加分店失败');
	        return $this->redirect(['/shops/index']);
		}

		//查询
		$queryParams = Yii::$app->request->queryParams;
		$pageSize = $queryParams['per-page'] ? $queryParams['per-page'] : 5;
		$model = Shops::find()
			->searchId($queryParams['id'])
			->searchName($queryParams['name'])
			->searchAddr($queryParams['addr'])
			->searchStatus($queryParams['status']);

		//分页
 		$pagination = new Pagination(['totalCount' => $model->count(), 'pageSize' => $pageSize]);
		$shops = $model->offset($pagination->offset)
    		->limit($pagination->limit)
    		->all();

		return $this->render('index',[
			'shops' => $shops,
			'pagination' => $pagination,
		]);
	}

	/**
	 * 更改连锁分店信息
	 */
	public function actionChange_info()
	{
		if ($post_data = Yii::$app->request->post()) {
			$shop = Shops::findOne($post_data['id']);
			if ($shop->load($post_data['Shops'], '') && $shop->save()) {
				OperateLog::insertLog(104, 
            		$shop->id,
            		Yii::$app->user->id,
            		Yii::$app->request->getUserIP(),
            		OperateLog::OPERATE_TYPE_APPEND,
            		'更改分店信息' . $shop->name
            		);
				Yii::$app->session->setFlash('success', '修改信息成功');
				return $this->redirect(['/shops/index']);
			} 
		}
		Yii::$app->session->setFlash('error', '修改失败');
		return $this->redirect(['/shops/index']);
	}

	/**
	 * 更改连锁分店状态
	 */
	public function actionChange_status()
	{
		if ($data = Yii::$app->request->post()) {
			$do_id = Yii::$app->user->id;
            $ip	= Yii::$app->request->getUserIP();

            $user = Shops::findOne($data['id']);
            switch ($data['eventType']) {
                case '3':
                    $user->status = 3;
                    $user->update();
                    OperateLog::insertLog('104',
                        $user->id, $do_id, $ip,
                        3, "删除分店 $user->name", $data['reason']);
                    Yii::$app->session->setFlash('success', '分店已删除');
                    break;
                case '2':
                    $user->status = 1;
                    $user->update();
                    OperateLog::insertLog('104',
                        $user->id, $do_id, $ip,
                        4, "启用分店 $user->name", $data['reason']);
                   	Yii::$app->session->setFlash('success', '分店已启用');
                    break;
                case '1':
                    $user->status = 2;
                    $user->update();
                    OperateLog::insertLog('104',
                        $user->id, $do_id, $ip,
                        5, "暂停分店 $user->name", $data['reason']);
                  	Yii::$app->session->setFlash('success', '分店已暂停');
                    break;
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
	        ->where(['module' => 104])
	        ->orderBy('created DESC')
	        ->searchDoUser($query_params['doUser'])
	        ->searchIp($query_params['ip'])
	        // ->searchType($query_params['type'])
	        ->searchLog($query_params['log'])
	        ->searchReason($query_params['reason']);
	        // ->searchAll($query_params['search']);

	    $pageInfo = $this->_page($dologs, $currentPage, $pageSize);

	    return $this->render('../auth/dolog.twig', [
	        'dologs' => $pageInfo['data'],
	        'pages' => $pageInfo['pages'],
	    ]);
	}
}