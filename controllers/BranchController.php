<?php

namespace app\controllers;

use app\models\SGoods;
use app\models\Orders;
use app\models\OrderDetail;
use Yii;
use yii\data\pagination;

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
			var_dump($data);exit;
			// $order = new Orders();
			// $order->order_id = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
			// $order->created = date('Y-m-d H:i:s');
			// $order->shop_id = $data['shop_id'];
			// $order->user_id = Yii::$app->user->id;
		}
	}
}