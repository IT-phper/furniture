<?php 

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;
use app\models\Shops;
use app\models\User;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="keywords" content="家具 销售管理 ERP">
    <meta name="description" content="家具销售管理系统">
    <meta name="author" content="ThemeBucket">
    <title><?= $this->title?></title>
    <link rel="shortcut icon" href="#" type="/image/png">

    <!--pickers css-->
    <link rel="stylesheet" type="text/css" href="/js/bootstrap-datepicker/css/datepicker-custom.css" />
    <link rel="stylesheet" type="text/css" href="/js/bootstrap-timepicker/css/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="/js/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="/js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="/js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />

    <!--icheck-->
    <link href="/js/iCheck/skins/minimal/minimal.css" rel="stylesheet">
    <link href="/js/iCheck/skins/square/square.css" rel="stylesheet">
    <link href="/js/iCheck/skins/square/red.css" rel="stylesheet">
    <link href="/js/iCheck/skins/square/blue.css" rel="stylesheet">

    <!--dashboard calendar-->
    <link href="/css/clndr.css" rel="stylesheet">

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="/js/morris-chart/morris.css">

    <!--common-->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/style-responsive.css" rel="stylesheet">

    <!-- swal -->
    <link rel="stylesheet" type="text/css" href="/dist/sweetalert.css">

    <script src="/js/jquery-1.10.2.min.js"></script>
    <script src="/dist/sweetalert.min.js"></script>

</head>

<body class="sticky-header">

<section>
    <!-- left side start-->
    <div class="left-side sticky-left-side">

        <!--logo and iconic logo start-->
        <div class="logo">
            <a href="/"><img src="/images/logo.png" alt=""></a>
        </div>

        <div class="logo-icon text-center">
            <a href="/"><img src="/images/logo_icon.png" alt=""></a>
        </div>
        <!--logo and iconic logo end-->

            <!--sidebar nav start-->
            <?php
                define('CONTROLLER', Yii::$app->controller->id);
                define('ACTION', Yii::$app->controller->action->id);   

                /**
                 * 侧边栏简化函数
                 */
                function siderbar_controller($url, $icon, $name) {
                    $siderbar = [
                        'branch/index' => ['branch/index', 'branch/sale', 'branch/list', 'branch/dolog', 'chart/shop', 'auth/shop'],
                        'goods/index' => ['goods/index', 'goods/dolog'],
                        'shops/index' => ['shops/index', 'shops/dolog'],
                        'auth/index' => ['auth/index', 'auth/role', 'auth/res', 'auth/batch_auth', 'auth/update_password', 'auth/dolog'],
                    ];
                    if (in_array(CONTROLLER . '/' . ACTION, $siderbar[$url])) {
                        echo '<li class="menu-list nav-active">';
                    } else {
                        echo '<li class="menu-list">';
                    }
                    echo '<a href="/' . $url . '"><i class="fa ' . $icon . '"></i> <span>' . $name . '</span></a>';
                }
                
                /**
                 * 侧边栏列表函数
                 */
                function siderbar_action($controller, $action, $name) {
                    if ($controller == CONTROLLER && $action == ACTION) {
                        echo '<li class="active">';
                    } else {
                        echo '<li>';
                    }
                    echo '<a href="/' . $controller . '/' . $action . '">' . $name . '</a></li>';
                }
            ?>
            <ul class="nav nav-pills nav-stacked custom-nav">
                <?php siderbar_controller('branch/index', 'fa-anchor', '我的分店管理'); ?>
                    <ul class="sub-menu-list">
                        <?php siderbar_action('branch', 'index', '商品库存信息'); ?>
                        <?php siderbar_action('branch', 'sale', '商品销售管理'); ?>
                        <?php siderbar_action('branch', 'list', '商品销售记录'); ?>
                         <?php siderbar_action('branch', 'dolog', '总部派发记录'); ?>
                        <?php siderbar_action('chart', 'shop', '销售额报表'); ?>
                        <?php siderbar_action('auth', 'shop', '分店管理员'); ?>
                    </ul>
                </li>
                <?php siderbar_controller('goods/index', 'fa-book', '商品信息综合管理'); ?>
                    <ul class="sub-menu-list">
                        <?php siderbar_action('goods', 'index', '商品信息'); ?>
                        <?php siderbar_action('goods', 'dolog', '操作日志'); ?>
                    </ul>
                </li>
                <?php siderbar_controller('shops/index', 'fa-hand-o-right', '连锁门店综合管理'); ?>
                    <ul class="sub-menu-list">
                        <?php siderbar_action('shops', 'index', '连锁门店列表'); ?>
                        <?php siderbar_action('shops', 'dolog', '操作日志'); ?>
                    </ul>
                </li>
                <?php siderbar_controller('auth/index', 'fa-user', '管理员与授权'); ?>
                    <ul class="sub-menu-list">
                        <?php siderbar_action('auth', 'index', '管理员列表'); ?>
                        <?php siderbar_action('auth', 'role', '管理员组列表'); ?>
                        <?php siderbar_action('auth', 'res', '资源列表'); ?>
                        <?php siderbar_action('auth', 'batch_auth', '权限分配'); ?>
                        <?php siderbar_action('auth', 'update_password', '修改密码'); ?>
                        <?php siderbar_action('auth', 'dolog', '操作日志'); ?>
                    </ul>
                </li>
            </ul>
            <!--sidebar nav end-->

        </div>
    </div>
    <!-- left side end-->
    
    <!-- main content start-->
    <div class="main-content" >

        <!-- header section start-->
        <div class="header-section">

            <!--toggle button start-->
            <a class="toggle-btn"><i class="fa fa-bars"></i></a>
            <!--toggle button end-->
            <?php 
                $shop_id = Yii::$app->user->identity->shop_id;
                if ($shop_id) {
                    $shop = Shops::findone($shop_id);
                echo '<button class="btn btn-info btn-lg" type="button" style="margin-left:3%">' . $shop->name . '</button>';
                $leaders = User::find()->where(['shop_id' => $shop_id, 'role' => 148, 'status' => 1])->all();
                $lead = '';
                foreach ($leaders as $leader) {
                    $lead .= $leader->real_name . ' ';
                } 
                echo '<button class="btn btn-info btn-lg" type="button" style="margin-left:3%">负责人: ' . $lead . '</button>';
                }
                
            ?>
            <!--notification menu start -->
            <div class="menu-right">
                <ul class="notification-menu">
                   <!--  <li>
                        <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge">5</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-head pull-right">
                            <h5 class="title">You have 5 Mails </h5>
                            <ul class="dropdown-list normal-list">
                                <li class="new">
                                    <a href="">
                                        <span class="thumb"><img src="images/photos/user1.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">John Doe <span class="badge badge-success">new</span></span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        <span class="thumb"><img src="images/photos/user2.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jonathan Smith</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="new"><a href="">Read All Mails</a></li>
                            </ul>
                        </div>
                    </li> -->
                    <li>
                        <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <?= Yii::$app->user->identity->username; ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                            <li><a href="#"><i class="fa fa-user"></i>  Profile</a></li>
                            <li><a href="/auth/update_password"><i class="fa fa-cog"></i>  修改密码</a></li>
                            <li><a href="/login/logout"><i class="fa fa-sign-out"></i> 注销</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
            <!--notification menu end -->

        </div>
        <!-- header section end-->

        <!-- page heading start-->
        <div class="page-heading">
            <?php echo Breadcrumbs::widget([
                'homeLink' => ['label' => '首页', 'url' => ['/']],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]); ?>
        </div> 
        <div>
        <?= Yii::$app->session->hasFlash('success') ?
                Alert::widget([
                    'options' => ['class' => 'alert-success'],
                    'body' => Yii::$app->session->getFlash('success'),
                ]) : '' ;
        ?>
        <?= Yii::$app->session->hasFlash('error') ?
                Alert::widget([
                    'options' => ['class' => 'alert-danger'],
                    'body' => Yii::$app->session->getFlash('error'),
                ]) : '' ;
        ?> 
        </div> 
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            <?php $this->beginBody() ?>
                <?= $content ?>
            <?php $this->endBody() ?>
        </div>
        <!--body wrapper end-->

        <!--footer section start-->
        <footer>
            2017 &copy; 家具销售管理系统 by <a href="" target="_blank">苏增光</a>
        </footer>
        <!--footer section end-->


    </div>
    <!-- main content end-->
</section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/modernizr.min.js"></script>
<script src="/js/jquery.nicescroll.js"></script>

<!--easy pie chart-->
<script src="/js/easypiechart/jquery.easypiechart.js"></script>
<script src="/js/easypiechart/easypiechart-init.js"></script>

<!--Sparkline Chart-->
<script src="/js/sparkline/jquery.sparkline.js"></script>
<script src="/js/sparkline/sparkline-init.js"></script>

<!--icheck -->
<script src="/js/iCheck/jquery.icheck.js"></script>
<script src="/js/icheck-init.js"></script>

<!-- jQuery Flot Chart-->
<script src="/js/flot-chart/jquery.flot.js"></script>
<script src="/js/flot-chart/jquery.flot.tooltip.js"></script>
<script src="/js/flot-chart/jquery.flot.resize.js"></script>


<!--Morris Chart-->
<script src="/js/morris-chart/morris.js"></script>
<script src="/js/morris-chart/raphael-min.js"></script>

<!--Calendar-->
<script src="/js/calendar/clndr.js"></script>
<script src="/js/calendar/evnt.calendar.init.js"></script>
<script src="/js/calendar/moment-2.2.1.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>

<!--common scripts for all pages-->
<script src="/js/scripts.js"></script>

<!--Dashboard Charts-->
<script src="/js/dashboard-chart-init.js"></script>

<!--pickers plugins-->
<script type="text/javascript" src="/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--pickers initialization-->
<script src="/js/pickers-init.js"></script>


</body>
</html>
