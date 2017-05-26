<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Orders;
use app\models\Shops;
use yii\helpers\Json;

class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
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
                return $this->redirect('/');
            }
            Yii::$app->session->setFlash('error','日期选择有误');
            return $this->redirect('/');
        }

        a:

        $from = isset($from) ? $from : date('Y-m-d', time()-6*60*60*24);
        $to = isset($to) ? $to : date('Y-m-d');
        // var_dump($from, $to);
        // exit;
        $day = (strtotime($to)-strtotime($from))/(24*60*60);

        $date = [];
        for ($diff = 60*60*24, $i = 0; $i <= $day; $i++) {
            $date[] = date('Y-m-d', strtotime($from)+$i*$diff);
        } 
        // var_dump($date);
        // exit;

        $array = [];

        for ($i = 0; $i <= $day ; $i++) {
        
            $array[] = Yii::$app->db
                ->createCommand("SELECT sum([[total]]) FROM {{orders}} where  created>=:b and created<:e")
                ->bindValue(':b', $date[$i] . ' 00:00:00')
                ->bindValue(':e', $date[$i] . ' 23:59:59')
                ->queryScalar();
        }
        // var_dump($array);exit;
        $string = '';
        foreach ($date as $v) {
           $string .= '"' . $v . '",';
        }

        //总额
        $total = array_sum($array);

        $data = Orders::find()
        ->select(['shop_id', 'total'])
        ->andWhere(['between', 'created', $from . ' 00:00:00', $to . ' 23:59:59'])
        ->orderBy('shop_id ASC')
        ->asArray()
        ->all();
        // var_dump($data);exit;
        $shops_list = Shops::getlist();
        // var_dump($shops_list);
        $array1 = [];
        foreach ($data as $v) {
            $array1[$v['shop_id']]['value'] += $v['total'];
            $array1[$v['shop_id']]['name'] = $shops_list[$v['shop_id']];
        }
        // var_dump(Json::encode(array_merge($array)));exit;
        return $this->render('index', [
            'data' => $array,
            'string' => $string,
            'total' => $total,
            'from' => $from,
            'to' => $to,
            'shop_sale_count' => Json::encode(array_merge($array1)),
            'shops_list' => array_column($array1, 'name'),
        ]);
    }

    public function translate_time($time){
        $array = explode('/', $time);
        $year = array_pop($array);
        array_unshift($array, $year);
        return implode('-', $array);
    }
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
