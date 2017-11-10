<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>登陆管理中心</title>
	<link href="admin/templates/css/login.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="admin/templates/js/jquery-1-9-0.js"></script>
    <script type="text/javascript" src="admin/templates/js/login.js"></script>
</head>
<body>
	<div class="ui-content">
		<div class="login_wrap">
			<div class="login_mian">
				<h3 class="login_title"><span>登录</span><i></i></h3>
				<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
				<ul class="form-list">
					<li>
						<em>账号</em>
						<input type="text" id="admin_name" name="admin_name" class="input" placeholder="请输入用户名" />
					</li>
					<li>
						<em>密码</em>
						<input type="password" id="admin_password" name="admin_password" class="input" placeholder="请输入密码" />
					</li>
<!--					<li>-->
<!--						<em>验证码</em>-->
<!--						<input type="text" id="sec_code" name="sec_code" class="input code-input" placeholder="验证码" />-->
<!--						<img class="code-img" src="admin.php?act=login&op=seccode" id="codeimage">-->
<!--						<a class="code-link" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='admin.php?act=login&op=seccode&t='+Math.random()">换一张</a>-->
<!--					</li>-->
				</ul>
				<p class="login-link"><input type="checkbox" id="cookietime" name="cookietime" value="1" />记住密码</p>
				<input type="button" value="登录" class="login_btn" onclick="checklogin();" />
			</div>
		</div>
	</div>
	<div class="ui-copyright">Copyright 2016-2017 All rights reserved.</div>
	<div class="alert-box">
		<div class="alert alert-danger"></div>
	</div>
</body>
</html>