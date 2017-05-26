<?php

namespace app\controllers;

use app\models\SGoods;
use app\models\Orders;
use app\models\OrderDetail;
use app\models\OperateLog;
use Yii;
use yii\data\pagination;
use yii\web\NotFoundHttpException;

class BranchController extends BaseController
{
	public function actionIndex()
	{	
		$shop_id = Yii::$app->user->identity->shop_id;

		//查询
		$queryParams = Yii::$app->request->queryParams;
		$pageSize = $queryParams['per-page'] ? $queryParams['per-page'] : 5;

		$model = SGoods::find()
		->joinWith('goods', 'goods.id = s_goods.fid')
		->where(['shop_id' => $shop_id])
		->searchName($queryParams['username']);

		//分页
 		$pagination = new Pagination(['totalCount' => $model->count(), 'pageSize' => $pageSize]);
		$s_goods = $model->offset($pagination->offset)
    		->limit($pagination->limit)
    		->all();
		return $this->render('index', [
			's_goods' => $s_goods,
			'pagination' => $pagination,
		]);
	}

	public function actionUpdate_price()
	{
		if ($data = Yii::$app->request->post()) {
			$goods = Sgoods::findOne($data['id']);
			$goods->sale_price = $data['sale_price'];
			if ($goods->save()) {
				Yii::$app->session->setFlash('success', '修改价格成功');
				return $this->redirect(['/branch/index']);
			}
		}
		Yii::$app->session->setFlash('error', '修改价格失败');
		return $this->redirect(['/branch/index']);
	}

	public function actionSale()
	{
		$shop_id = Yii::$app->user->identity->shop_id;

		$model = SGoods::find()
		->joinWith('goods', 'goods.id = s_goods.fid')
		->where(['shop_id' => $shop_id])
		->andWhere(['not', ['sale_price' => null]])
		->andWhere(['>', 'sale_num', 0])
		->all();
		// var_dump($model);exit;
		return $this->render('sale',[
			'model' => $model,
			'shop_id' => $shop_id,
		]);
	}

	public function actionDo_sale()
	{
		if ($data = Yii::$app->request->post()) {
			// var_dump($data);exit;
			$order = new Orders();
			$order->order_id = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);			
			$order->created = date('Y-m-d H:i:s');
			$order->shop_id = $data['shop_id'];
			$order->user_id = Yii::$app->user->id;
			$order->total = $data['total'];
			$order->save();

			foreach ($data['Sale'] as $k => $v) {
				if ($v['order_num']) {

					$detail = new OrderDetail();
					$detail->frid = $order->id;
					$detail->goods_id = $k;
					$detail->order_num = $v['order_num'];
					$detail->retail_price = $v['retail_price'];
					$detail->save();	

					$s_goods = SGoods::findOne($v['id']);
					$s_goods->sale_num -= $v['order_num'];
					$s_goods->save();

				}
			}

			Yii::$app->session->setFlash('success', '商品销售成功');
			return $this->redirect(['/branch/sale']);
		}
	}

	public function actionList()
	{
		$shop_id = Yii::$app->user->identity->shop_id;

		//查询
		$queryParams = Yii::$app->request->queryParams;
		$pageSize = $queryParams['per-page'] ? $queryParams['per-page'] : 5;
		$model = Orders::find()
			->innerJoin('user', 'user.id = orders.user_id')
			->where(['orders.shop_id' => $shop_id])
			->searchUsername($queryParams['username'])
			->searchOrder_id($queryParams['order_id']);


		//分页
 		$pagination = new Pagination(['totalCount' => $model->count(), 'pageSize' => $pageSize]);
		$orders = $model->offset($pagination->offset)
    		->limit($pagination->limit)
    		->all();

		return $this->render('list', [
			'orders' => $orders,
			'pagination' => $pagination,
		]);
	}

	public function actionDetail()
	{
		$frid = Yii::$app->request->get('id');
		if (!$frid) {
			throw new NotFoundHttpException('The requested page does not exist.');
		} else {
			$order = Orders::findOne($frid);
			if ($order == null || $order->shop_id !== Yii::$app->user->identity->shop_id) {
				throw new NotFoundHttpException('非法操作');
			}
			$model = OrderDetail::find()->where(['frid' => $frid])->all();
			return $this->render('detail', [
				'model' => $model,
				'order' => $order,
			]);
		}
	}

	 /**
	 * 操作日志
	 */
	public function actionDolog()
	{
		$shop_id = Yii::$app->user->identity->shop_id;

	    $query_params = Yii::$app->request->queryParams;
	    // list($query_params['start'], $query_params['end']) = explode('--', $query_params['time']);
	    $currentPage = $query_params['page'] ? $query_params['page'] : 1;
	    $pageSize = $query_params['per-page'] ? $query_params['per-page'] : 5;

	    $dologs = OperateLog::find()
	        ->innerJoinWith('user')
	        ->where(['module' => 103])
	        ->orderBy('created DESC')
	        ->searchDoUser($query_params['doUser'])
	        ->searchDoShop($shop_id)
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