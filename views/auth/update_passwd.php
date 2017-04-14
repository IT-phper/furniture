<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<style type="text/css">
 .text-danger{
    display: none;
}
.j-save-btn {
	margin-left: 10px;
}
.panel.panel-default{
    border:0;
}
.panel-default > .panel-heading{
    color: #66CDAA;
    background: #68838B;
    height: auto;
    border:0;
}
</style>
<body>
	<div id="change-pwd" class="panel panel-default" >
		<div class="panel-heading">
			<h3 class="">修改密码</h3>
		</div>
		<form action="/auth/update_password" method="post" class="form-horizontal">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken()?>">
			<div class="panel-body">
				<div class="form-group">
                    <label class="control-label col-md-3" for="administrators-new-pwd">密码</label>
                    <div class="col-md-6">
                        <input type="password" id="administrators-new-pwd" class="form-control" placeholder="" name="AdminUsers[password]">
                        <span class="pull-left">密码至少16位，且包含一个数字、一个大写字母、一个小写字母和一个英文半角符号</span>
                        <span class="text-danger">密码不符合规则，请重新输入</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3" for="administrators-again-new-pwd">重复新密码</label>
                    <div class="col-md-6">
                        <input type="password" id="administrators-again-new-pwd" class="form-control" placeholder="" name="AdminUsers[password_confirm]">
                        <span class="text-danger">两次输入不一致，请重新输入</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2 col-md-offset-5">
                        <button type="submit" class="btn btn-primary j-save-btn">保存</button>
                    </div>
                </div>
			</div>
		</form>
	</div>
	<script type="text/javascript">
		$(function() {

			(function(){

				var pwd = '';
            	var confirmPwd = '';
            	var validation = {
                	authenPwd:function($pwd, $confirmPwd) {
                    	pwd = $pwd.val().trim();
                    	var reg = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[~!@#$%^&*()_+`\-={}:";'<>?,.\/]).{16,}$/;

                    	if(!reg.test(pwd)) {
                        	$pwd
                              	  .next().hide()
                        	.end().nextAll('.text-danger').show();
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

            	$('.j-save-btn').click(function() {
            		var hasError = $('#change-pwd .text-danger').is(':visible');
            		if(hasError) return false;
            	});

			})();

		});
	</script>
</body>
</html>