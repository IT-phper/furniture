<?php 

use app\models\Role;
use app\models\Shops;
use app\models\User;
use yii\widgets\LinkPager;

$this->title = '管理员列表';
$this->params['breadcrumbs'][] = $this->title;

?>

<style type="text/css">
.form-control.col-md-3 {
    width: 25%;
}
.form-control.col-md-6 {
    width: 50%;
}
.pagination-box .input-group {
    margin: 20px 10px;
}
.pageSize-box {
    display: inline-block;
    width: 75px;
    margin-left: 10px;
    vertical-align: middle;
}
#modal-auth-operation .reason-box,#changes-pwd .reason-box{
    border-top:1px dashed #666;
    line-height:24px;
    padding:5px 0;
}
#modal-auth-operation .reason-box > p,#changes-pwd .reason-box > p {
    padding-left: 20px;
}
#modal-auth-operation .inner-box ,#changes-pwd .inner-box{
    background-color:#ddd;
    margin-bottom:20px;
}
.text-danger{
    display: none;
}
.nav-tabs > li.active > a{
    background-color: #eee;
}
</style>
<div class="btn-group">
    <button class="btn btn-primary j-btn-add-server" type="button" data-toggle="modal" data-target="#modal-add-admin">添加分店管理员</button>
</div>
<div id="modal-add-admin" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" raia-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加分店管理员</h4>
            </div>
            <form class="form-horizontal" method="post" action="/auth/new_shop_user">
                <div class="modal-body">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken();?>">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="administrators-number">账号</label>
                        <div class="col-md-6">
                            <input type="text" id="administrators-number" class="form-control" placeholder="" name="AdminUsers[username]" required>
                            <span class="pull-left">请填写个人邮箱</span>
                            <span class="text-danger">帐号不符合规则，请重新输入</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="administrators-name">真实姓名</label>
                        <div class="col-md-6">
                            <input type="text" id="administrators-name" class="form-control" placeholder="" name="AdminUsers[real_name]" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="administrators-pwd">密码</label>
                        <div class="col-md-6">
                            <input type="password" id="administrators-pwd" class="form-control" placeholder="" name="AdminUsers[password]">
                            <span class="pull-left">密码至少16位，且包含一个数字、一个大写字母、一个小写字母和一个英文半角符号</span>
                            <span class="text-danger">密码不符合规则，请重新输入</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="administrators-again-pwd">确认密码</label>
                        <div class="col-md-6">
                            <input type="password" id="administrators-again-pwd" class="form-control" placeholder="" name="AdminUsers[password_confirm]">
                            <span class="text-danger">两次输入不一致，请重新输入</span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div>
    <form id="localSearch" action="/auth/index" method="get">
        <table id="j-server-table" class="table table-bordered table-hover">
            <tr>
                <th class="">账号</th>
                <th style="width:200px;">真实姓名</th>
                <th style="width:200px;">状态</th>
                <th style="width:200px;">添加时间</th>
                <th style="width:200px;">操作</th>
            </tr>
            <tr class="send">
                <td><input type="text" class="form-control" name="username"  value="<?= Yii::$app->request->queryParams['username'] ?>" ></td>
                <td><input type="text" class="form-control" name="real_name" value="<?= Yii::$app->request->queryParams['real_name'] ?>"></td>
                <td>
                    <select class="form-control" name="status" style="padding:6px 2px">
                        <?php
                            $status = [
                                ' ' => '全部',
                                '1' => '启用',
                                '2' => '暂停'
                            ];
                            foreach ($status as $k => $v) {
                                $default = Yii::$app->request->queryParams['status'] == $k ? 'selected' : '';
                                echo "<option value= {$k} {$default}>{$v}</option>";
                            }
                        ?>
                    </select>
                </td>
                <td><input id="pickTime" class="form-control" type="text" name="time" value="" readonly></td>
                <td></td>
            </tr>

            <?php foreach ($users as $user) { ?>
            <tr data-id=<?= $user['id'] ?>>
                <td data-canEdit="true" data-type="input"><span><?= $user['username'] ?></span></td>
                <td data-canEdit="true" data-type="input"><span><?= $user['real_name']?></span></td>
                <?= 
                   $user['status'] == User::USER_TABLE_STATUS_ACTIVE ? '<td>启用</td>' : '<td>暂停</td>';
                ?>
                <td data-canEdit="true" data-type="input" style="min-width:152px;"><span><?= $user['created'] ?></span></td>
                <td>
                    <div class="btn-group btn-group-xs">
                        <button class="btn btn-danger" data-toggle="modal" data-target="#modal-auth-operation" data-mode="single" data-event="delete" type="button">删除</button>
                        <button class="btn btn-default" data-toggle="modal" data-target="#modal-auth-operation" data-mode="single" data-event="<?= $user['status'] == User::USER_TABLE_STATUS_ACTIVE ? 'pause' : 'enable'?>" type="button"><?= $user['status'] == User::USER_TABLE_STATUS_ACTIVE ? '暂停' : '启用'?></button>
                        <button class="btn btn-default" data-toggle="modal" data-target="#changes-pwd" data-mode="single" data-event="changePwd" type="button">修改密码</button>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="pagination-box pull-right">
            <div class="input-group pull-left">
                <label class="control-label">每页显示</label>
                <div class="pageSize-box">
                    <select id="pageSize" class="form-control" name="per-page">
                        <?php 
                            $page = Yii::$app->params['per-page'];
                            foreach ($page as $per_page) {
                                $default = Yii::$app->request->queryParams['per-page'] == $per_page ? 'selected' : '';
                                echo "<option value= {$per_page} {$default}>{$per_page}</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <nav class="pull-right">
               <?php
                    echo LinkPager::widget([
                        'pagination' => $pagination,
                    ]);
               ?>
            </nav>
        </div>
    </form>
</div>
<div id="modal-auth-operation" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title">确认<span class="tips">删除</span>该管理员吗?</h4>
            </div>
            <div class="modal-body clearfix">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-10 col-md-offset-1">
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary j-save-btn">保存</button>
            </div>
        </div>
    </div>
</div>
<div id="changes-pwd" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title">修改密码</h4>
            </div>
            <form action="/admin/auth/index" class="form-horizontal" method="post">
                <div class="modal-body">
                    <input type="hidden" name="">
                    <div class="form-group">
                        <label class="control-label col-md-3" for="administrators-new-pwd">密码</label>
                        <div class="col-md-6">
                            <input type="password" id="administrators-new-pwd" class="form-control" placeholder="" name="administrators-new-pwd">
                            <span class="pull-left">密码至少16位，且包含一个数字、一个大写字母、一个小写字母和一个英文半角符号</span>
                            <span class="text-danger">密码不符合规则，请重新输入</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" for="administrators-again-new-pwd">重复新密码</label>
                        <div class="col-md-6">
                            <input type="password" id="administrators-again-new-pwd" class="form-control" placeholder="" name="administrators-again-new-pwd">
                            <span class="text-danger">两次输入不一致，请重新输入</span>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary j-save-btn">保存</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function() {
        var pwd = '';
        var confirmPwd = '';
        var validation = {
            authenPwd:function($pwd, $confirmPwd) {
                pwd = $pwd.val().trim();
                var reg = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[~!@#$%^&*()_+`\-={}:";'<>?,.\/]).{16,}$/;

                if(!reg.test(pwd)) {
                    $pwd.next().hide().end().nextAll('.text-danger').show();
                }

                if(pwd !== confirmPwd && confirmPwd) {
                    $confirmPwd.next().show();
                }
            },
            confirmPwd: function($confirmPwd) {
                confirmPwd = $confirmPwd.val().trim();

                if(confirmPwd !== pwd) {
                    $confirmPwd.next().show();
                    return;
                }

                if(confirmPwd === pwd) {
                    $confirmPwd.next().hide();
                }
            }
        };
        //验证账号
        $('#administrators-number').blur(function() {
            var email = $(this).val().trim();
            var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;

            if(!reg.test(email)) {
                $(this)
                        .next().hide()
                  .end().nextAll('.text-danger').show();

                return;
            }
        }).focus(function() {

            $(this)
                  .next().show()
            .end().nextAll('.text-danger').hide();
        });

        //验证密码
        $('#administrators-pwd').blur(function() {

            var $confirmPwd = $('#administrators-again-pwd');
            validation.authenPwd($(this), $confirmPwd);
        }).focus(function() {

            $(this)
                    .next().show()
              .end().nextAll('.text-danger').hide();
        });

        //确认密码
        $('#administrators-again-pwd').blur(function() {
            validation.confirmPwd($(this));
        });

        $('#modal-add-admin form').submit(function() {
            var hasError = $(this).find('.text-danger').is(':visible');
            if(hasError) {
                return false;
            }
        });

        //修改密码
        $('#changes-pwd').on('show.bs.modal', function(event) {
            var $obj = $(event.relatedTarget);
            var dataId = $obj.parents('tr').attr('data-id');

            $(this).find('.modal-footer .j-save-btn').attr({
                'data-id': dataId
            });
        });

        $('#administrators-new-pwd').blur(function() {

            var $confirmNewPwd = $('#administrators-again-new-pwd');
            validation.authenPwd($(this), $confirmNewPwd);
        }).focus(function() {
            $(this)
                    .next().show()
              .end().nextAll('.text-danger').hide();
        });

        $('#administrators-again-new-pwd').blur(function() {
            validation.confirmPwd($(this));
        });

        $('#changes-pwd .j-save-btn').on('click', function() {
            var $modal = $(this).parents('.modal');
            var hasError = $modal.find('.text-danger').is(':visible');

            if(!hasError && pwd) {

                var id = $(this).attr('data-id');
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                $.post('/auth/admin_change_password', {
                    id: id,
                    pwd: pwd,
                    confirmPwd: confirmPwd,
                    _csrf: csrfToken
                }, function(){
                    window.location.reload();
                });
            }
        });

        //删除，暂停，启用
        var authEdit = {
            eventsType: {
                'delete': 3,
                'pause': 1,
                'enable': 2
            },
            save: function($obj) {
                var $modal = $obj.parents('.modal');
                var dataEvent = $obj.attr('data-event');

                var eventType = this.eventsType[dataEvent];

                var id = $obj.attr('data-id');
                var reason = $modal.find('textarea').val();

                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                var options = {
                    id: id,
                    eventType: eventType,
                    reason: reason,
                    _csrf: csrfToken
                }

                $.post('/auth/admin_change_status', options, function(){
                    window.location.reload();
                });
            }
        };

        $('#modal-auth-operation').on('show.bs.modal', function(event) {
            var $obj = $(event.relatedTarget);
            var text = $obj.text();
            var dataId = $obj.parents('tr').attr('data-id');
            var eventType = $obj.attr('data-event');

            //数据迁移
            $(this).find('.modal-title .tips').text(text).end()
                    .find('.modal-footer .j-save-btn').attr({
                        'data-id': dataId,
                        'data-event': eventType
                    });
        });

        $('#modal-auth-operation .j-save-btn').on('click', function() {
            authEdit.save($(this));
        });

        //搜索功能实现
        // 回车键搜索
        $(document).on('keydown', function(event) {
            var code = event.keyCode;
            if(code === 13) {
                $('#localSearch').submit();
            }
        });
        // 改变select多选框搜索
        $('#j-server-table .send .form-control:not("input")').on('change', function() {
            $('#localSearch').submit();
        });
        //改变分页时搜索
        $('#pageSize').on('change', function() {
            $('#localSearch').submit();
        });
    })();


</script>