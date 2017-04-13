<?php 

namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\models\LoginForm;
use app\models\User;
use app\models\UserIdentity;

class LoginController extends Controller
{
	public $layout = false;

	public function actionIndex()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->redirect(['/']);
		}
		if ($post_data = Yii::$app->request->post()) {
			if (User::validatePassword($post_data['username'], $post_data['password'])) {
				Yii::$app->user->login(UserIdentity::findByUsername($post_data['username']), $post_data['rememberMe'] == 'rememberMe' ? 3600*24*30 : 0);
			} else {
				Yii::$app->session->setFlash('error', '您输入的账号或密码有误');
			}
		}
		return $this->render('login');
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->redirect(['/login/index']);
	}
}

?>