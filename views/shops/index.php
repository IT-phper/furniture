<?php

use yii\widgets\LinkPager;

$this->title = '连锁门店列表';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="btn-group">
    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal-add-admin">添加分店</button>
</div>
<div id="modal-add-admin" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" raia-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加分店</h4>
            </div>
            <form class="form-horizontal" method="post">
                <div class="modal-body">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken();?>">
                    <div class="form-group">
                        <label class="control-label col-md-3" for="shops-name">分店名称</label>
                        <div class="col-md-8">
                            <input type="text" id="shops-name" class="form-control" placeholder="" name="Shops[name]" required>
                            <!-- <span class="pull-left">请填写分店名称</span> -->
                            <!-- <span class="text-danger">帐号不符合规则，请重新输入</span> -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" for="shops-phone">电话</label>
                        <div class="col-md-8">
                            <input type="tel" id="shops-phone" class="form-control" placeholder="" name="Shops[phone]" pattern="(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$" title="电话格式不正确">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" for="shops-addr">地址</label>
                        <div class="col-md-8">
                            <input type="text" id="shops-addr" class="form-control" name='Shops[addr]'>
                       
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="control-label col-md-3" for="shops-email">Email</label>
                        <div class="col-md-8">
                            <input type="email" id="shops-email" class="form-control" placeholder="" name="Shops[email]">
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
    <form id="localSearch" action="/shops/index" method="get">
        <table id="j-server-table" class="table table-bordered table-hover">
            <tr>
                <th style="width:80px;">分店ID</th>
                <th style="width:130px;">分店名称</th>
                <th style="width:130px;">电话</th>
                <th>地址</th>
                <th style="width:100px;">邮件</th>
                <th>状态</th>
                <th style="width:100px">分店负责人</th>
                <th style="width:100px;">创建时间</th>
                <th style="width:140px;">操作</th>
            </tr>
            <tr class="send">
                <td><input type="text" class="form-control" name="id"  value="<?= Yii::$app->request->queryParams['id'] ?>" ></td>
                <td><input type="text" class="form-control" name="name" value="<?= Yii::$app->request->queryParams['name'] ?>"></td>
                <td></td>
                <td><input type="text" class="form-control" name="addr" value="<?= Yii::$app->request->queryParams['addr'] ?>"></td></td>
                <td></td>
                <td>
                    <select class="form-control" name="status" style="padding:6px 2px">
                        <?php
                            $status = [
                                ' ' => '全部',
                                '1' => '正常营业',
                                '2' => '暂停营业'
                            ];
                            foreach ($status as $k => $v) {
                                $default = Yii::$app->request->queryParams['status'] == $k ? 'selected' : '';
                                echo "<option value= {$k} {$default}>{$v}</option>";
                            }
                        ?>
                    </select>
                </td>
                <td></td>
                <td><input id="pickTime" class="form-control" type="text" name="time" value="" readonly></td>
                <td></td>
            </tr>

            <?php foreach ($shops as $shop) { ?>
            <tr data-id=<?= $shop['id'] ?>>
                <td data-canEdit="true" data-type="input"><span><?= $shop['id'] ?></span></td>
                <td data-canEdit="true" data-type="input"><span><?= $shop['name']?></span></td>
                <td data-canEdit="true" data-type="input"><span><?= $shop['phone']?></span></td>
                <td data-canEdit="true" data-type="input"><span><?= $shop['addr']?></span></td>
                <td data-canEdit="true" data-type="input"><span><?= $shop['email']?></span></td>
                <?php
                   echo $shop['status'] == 1 ? '<td>正常营业</td>' : '<td>暂停营业</td>';
                ?>
                <td data-canEdit="true" data-type="input">
                        <span>
                        <?php 
                            foreach ($shop['user'] as $user) {
                                if ($user->status == 1 && $user->role == 148) {
                                    echo $user->real_name . ' ';
                                }
                            }
                        ?>  
                        </span>
                </td>
                <td data-canEdit="true" data-type="input" style="min-width:100px;"><span><?= date('Y-m-d', strtotime($shop['created'])) ?></span></td>
                <td>
                    <div class="btn-group btn-group-xs">
                        <button class="btn btn-default" data-toggle="modal" data-target="#modal-edit" type="button">编辑</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#modal-auth-operation" data-mode="single" data-event="delete" type="button">删除</button>
                        <button class="btn btn-default" data-toggle="modal" data-target="#modal-auth-operation" data-mode="single" data-event="<?= $shop['status'] == 1 ? 'pause' : 'enable'?>" type="button"><?= $shop['status'] == 1 ? '暂停' : '开启'?></button>
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
<div id="modal-edit" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
          <form action="/shops/change_info" class="form-horizontal" method="post">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title">更新管理员组</h4>
            </div>
            <div class="modal-body clearfix">
                <input type="hidden" name="id">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken()?>">
                <div class="form-group">
                    <label class="control-label col-md-3" for="shops-name">分店名称</label>
                    <div class="col-md-8">
                        <input type="text" id="shops-name" class="form-control" placeholder="" name="Shops[name]" required>
                        <!-- <span class="pull-left">请填写分店名称</span> -->
                        <!-- <span class="text-danger">帐号不符合规则，请重新输入</span> -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3" for="shops-phone">电话</label>
                    <div class="col-md-8">
                        <input type="tel" id="shops-phone" class="form-control" placeholder="" name="Shops[phone]" pattern="(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$" title="电话格式不正确">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3" for="shops-addr">地址</label>
                    <div class="col-md-8">
                        <input type="text" id="shops-addr" class="form-control" name='Shops[addr]'>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-md-3" for="shops-email">Email</label>
                    <div class="col-md-8">
                        <input type="email" id="shops-email" class="form-control" placeholder="" name="Shops[email]">
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary j-save-btn">保存</button>
            </div>
          </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    //编辑管理员 表单id信息
    $('#modal-edit').on('show.bs.modal', function (event) {
        var $trigger = $(event.relatedTarget);
        var tr = $trigger.parents('tr')
        var id = tr.data('id');
        $(this).find('input[name="id"]').val(id);
        var name = tr.find("span:eq(1)").html(); 
        $(this).find('input[name="Shops[name]"]').val(name);
        var phone = tr.find("span:eq(2)").html();
        $(this).find('input[name="Shops[phone]"]').val(phone);
        var addr = tr.find("span:eq(3)").html();
        $(this).find('input[name="Shops[addr]"]').val(addr);
        var email = tr.find("span:eq(4)").html();
        $(this).find('input[name="Shops[email]"]').val(email);
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

            $.post('/shops/change_status', options, function(){
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
</script>