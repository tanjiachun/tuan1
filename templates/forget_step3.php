<?php include(template('common_header'));?>
    <style>
        .register-hd{width:1180px;border-bottom: 1px solid #ddd;padding-bottom: 20px;margin:0 auto;margin-top: 20px;}
        .register-form .code-input .lr-input {
            width: 216px;
            vertical-align: middle;
        }
    </style>
	<div class="register-hd">
			<a href="index.php">
				<img src="templates/images/logo.png">
			</a>
        <span style="font-size: 16px;font-weight: 700;margin-left: 5px;">重置密码</span>
	</div>
	<div class="register-c zhmm-box">
		<div class="zh-title">
			<ul>
				<li class="active"><span>1</span><br>验证信息</li>
				<li class="active"><u></u></li>
				<li class="active"><span>2</span><br>重置密码</li>
				<li class="active"><u></u></li>
				<li class="active"><span>√</span><br>　完成</li>
			</ul>
		</div>
		<div class="zh-success">
			<i class="iconfont icon-check"></i>
			<h1>恭喜你，找回密码成功</h1>
			<p><a href="index.php?act=login" class="btn btn-primary">返回登录</a></p>
		</div>
	</div>