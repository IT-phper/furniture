<?php

namespace app\controllers;

use app\models\Orders;
use Yii;

class ChartController extends BaseController
{
	public function translate_time($time){
		$array = explode('/', $time);
		$year = array_pop($array);
		array_unshift($array, $year);
		return implode('-', $array);
	}

	public function actionShop()
	{
		if ($data = Yii::$app->request->get()) {
			if ($data['from'] && $data['to']) {
				$from = self::translate_time($data['from']);
				$to = self::translate_time($data['to']);
				$today = date('Y-m-d');
				if ($from < $to && $from < $today) {
					// var_dump($from, $to);
					if ($to > $today) $to = $today;
					goto a;
				}
				Yii::$app->session->setFlash('error','日期选择有误');
				return $this->redirect('/chart/shop');
			}
			Yii::$app->session->setFlash('error','日期选择有误');
			return $this->redirect('/chart/shop');
		}

		a:

		$from = isset($from) ? $from : date('Y-m-d', time()-6*60*60*24);
		$to = isset($to) ? $to : date('Y-m-d');

		$shop_id = Yii::$app->user->identity->shop_id;
		$day = (strtotime($to)-strtotime($from))/(24*60*60);

		$date = [];
		for ($diff = 60*60*24, $i = 0; $i <= $day; $i++) {
			$date[] = date('Y-m-d', strtotime($from)+$i*$diff);
		} 
		

		$array = [];

		for ($i = 0; $i <= $day ; $i++) {
		
			$array[] = Yii::$app->db
				->createCommand("SELECT sum([[total]]) FROM {{orders}} where shop_id=:shop_id and created>=:b and created<:e")
				->bindValue(':shop_id', $shop_id)
				->bindValue(':b', $date[$i] . ' 00:00:00')
				->bindValue(':e', $date[$i] . ' 23:59:59')
				->queryScalar();
		}

		$string = '';
		foreach ($date as $v) {
           $string .= '"' . $v . '",';
        }

        //总额
        $total = array_sum($array);

		return $this->render('shop', [
			'data' => $array,
			'string' => $string,
			'total' => $total,
			'from' => $from,
			'to' => $to,
		]);
	}
}