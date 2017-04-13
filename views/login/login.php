<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="#" type="image/png">

    <title>Login</title>

    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/style-responsive.css" rel="stylesheet">
</head>

<body class="login-body">

<div class="container">

    <form class="form-signin" method="post">
        <input type="hidden" name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken();?>">
        <div class="form-signin-heading text-center">
            <h1 class="sign-title">登录</h1>
            <img src="/images/login-logo.png" alt=""/>
        </div>
        <div class="login-wrap">
            <input name="username" type="text" class="form-control" placeholder="管理员邮箱" autofocus>
            <input name="password" type="password" class="form-control" placeholder="密码">
            <?= Yii::$app->session->hasFlash('error') ?
                yii\bootstrap\Alert::widget([
                    'options' => ['class' => 'alert-danger'],
                    'body' => Yii::$app->session->getFlash('error'),
                ]) : '' ;
            ?> 
            <button class="btn btn-lg btn-login btn-block" type="submit">
                <i class="fa fa-check"></i>
            </button>

            <div>
                <label class="checkbox">
                    <input name="rememberMe" type="checkbox" value="rememberMe"> Remember me
                </label>
            </div>
        </div>
    </form>

</div>


<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/modernizr.min.js"></script>

</body>
</html>
