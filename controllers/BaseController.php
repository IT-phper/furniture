<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;


class BaseController extends Controller
{
	public $layout = 'main'; 

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            Yii::$app->getResponse()->redirect('/login')->send();
            return;
        }
        return parent::beforeAction($action);
    }
}

