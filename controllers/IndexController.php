<?php

namespace app\controllers;

use yii\web\Controller;

class IndexController extends Controller
{
	$this->layout = false;
	
	public function actionNo_auth()
	{
		return $this->render('no_auth');
	}
}

