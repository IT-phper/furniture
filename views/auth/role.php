<?php 

use yii\widgets\LinkPager;

$this->title = '管理员组列表';
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
    width: 65px;
    margin-left: 10px;
    vertical-align: middle;
}
#modal-server-operation .reason-box,#changes-pwd .reason-box{
    border-top:1px dashed #666;
    line-height:24px;
    padding:5px 0;
}
#modal-server-operation .reason-box > p,#changes-pwd .reason-box > p {
    padding-left: 20px;
}
#modal-server-operation .inner-box ,#changes-pwd .inner-box{
    background-color:#ddd;
    margin-bottom:20px;
}
.nav-tabs > li.active > a{
    background-color: #eee;
}
</style>
<!-- <div class="btn-group">
	<button class="btn btn-primary j-btn-add-server" type="button" data-toggle="modal" data-target="#modal-add-usergroup">添加管理员组</button>
</div> -->

<div id="modal-add-usergroup" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" raia-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加管理员组</h4>
            </div>
            <form action="/auth/role" id="j-checkall" class="form-horizontal" method="post">
                <div class="modal-body">
                    <input type="hidden" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" name="_csrf" />
                    <div class="form-group">
                        <label class="control-label col-md-3" for="Role[name]">管理员组名称</label>
                        <div class="col-md-9">
                            <input type="text" id="ugroup-name" class="form-control" name="Role[name]" required>
                            <span id="helpBlock" class="help-block">请填写管理员组名称</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
 <form id="localSearch" action="/auth/role" method="get">
    <table id="j-server-table" class="table table-bordered table-hover">
        <tr>
            <th style="width:152px;">管理员组名称</th>
            <th>包含管理员</th>
            <th style="width:152px;">添加时间</th>
            <!-- <th style="min-width:100px;">操作</th> -->
        </tr>

        <tr class="send">
            <td><input type="text" class="form-control" name="name" value="<?= Yii::$app->request->queryParams['name']?>"></td>
            <td><input type="text" class="form-control" name="username" value="<?= Yii::$app->request->queryParams['username']?>"></td>
            <td><input id="pickTime" class="form-control" type="text" name="time" value="" readonly></td>
            <!-- <td></td> -->
        </tr>
        <?php foreach ($data as $role) { ?>
        <tr data-id=<?= $role['role']?>>
            <td data-canEdit="true" data-type="input"><span><?= $role['name']?></span></td>
            <td data-canEdit="true" data-type="input">
                <?php
                    foreach ($role->user as $v) {
                        if ($v->status == 1) {
                            echo "<span>{$v->real_name}</span> ";
                        }
                    }
                ?>
            </td>
            <td data-canEdit="true" data-type="input"><span><?= $role['created']?></span></td>
           <!--  <td>
                <div class="btn-group btn-group-xs">
                    <button class="btn btn-danger" data-toggle="modal" data-target="#modal-server-operation" data-mode="single" data-event="delete" type="button">删除</button>
                    <button class="btn btn-default" data-toggle="modal" data-target="#modal-edit-usergroup" data-mode="single" data-event="enable" type="button">编辑</button>
                </div>
            </td> -->
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

<div id="modal-edit-usergroup" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" raia-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">编辑管理员组</h4>
            </div>
            <form action="/auth/update_role" id="j-checkall" class="form-horizontal" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <input type="hidden" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" name="_csrf" />
                    <div class="form-group">
                        <label class="control-label col-md-3" for="Role[name]">管理员组名称</label>
                        <div class="col-md-9">
                            <input type="text" id="ugroup-name" class="form-control"  name="name" required>
                            <span id="helpBlock" class="help-block">请填写管理员组名称</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-server-operation" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title">删除管理员组</h4>
            </div>
            <form action="/auth/delete_role" class="form-horizontal" method="post">
            <div class="modal-body clearfix">
                <input type="hidden" name="id">
                     <input type="hidden" name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken()?>">
                    <div class="form-group required">
                      <label class="control-label col-md-2">备注：<span class="glyphicon glyphicon-asterisk text-danger"></span></label>
                        <div class="col-md-10 col-md-offset-1">
                            <textarea class="form-control" rows="5" name="reason"></textarea>
                        </div>
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

<script>
	$(function() {
		//编辑管理员组 表单id信息
	    $('#modal-edit-usergroup').on('show.bs.modal', function (event) {
	        var $trigger = $(event.relatedTarget);
	        var id = $trigger.parents('tr').data('id');
	        $(this).find('input[name="id"]').val(id);
	    });
	    //删除管理员组 表单id信息
	    $('#modal-server-operation').on('show.bs.modal', function (event) {
	        var $trigger = $(event.relatedTarget);
	        var id = $trigger.parents('tr').data('id');
	        $(this).find('input[name="id"]').val(id);
	    });

        //搜索
        // 回车键搜索
        $(document).on('keydown', function(event) {
            var code = event.keyCode;
            if(code === 13) {
                $('#localSearch').submit();
            }
        });        
        //改变分页时搜索
        $('#pageSize').on('change', function() {
            $('#localSearch').submit();
        });
	});
</script>