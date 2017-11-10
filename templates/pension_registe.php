<?php include(template('common_header'));?>
	<div class="register-hd">
		<h2>
			<a href="index.php">
				<img src="templates/images/logo.png">
			</a>
		</h2>
		<div class="top-progress zhmm-box">
			<div class="zh-title">
				<ul>
					<li class="active"><span>1</span><br>注册</li>
					<li><u></u></li>
					<li><span>2</span><br>实名认证</li>
					<li><u></u></li>
					<li><span>3</span><br>完成注册</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="register-c">
		<h1>
			<a class="active" id="register_tab">养老机构注册</a>
			<a id="login_tab">已有本站账号</a>
		</h1>
        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
		<table class="register-form" id="register_box">
			<tr>
				<th>手机号码</th>
				<td>
					<input id="member_phone" name="member_phone" class="lr-input" type="text" placeholder="请输入手机号">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>验证码</th>
				<td class="code-input">
					<input id="phone_code" name="phone_code" class="lr-input code-input" type="text">
					<span class="take-code">获取验证码</span>
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>密码</th>
				<td>
					<input id="member_password" name="member_password" class="lr-input" type="password" placeholder="请输入密码">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>确认密码</th>
				<td>
					<input id="member_password2" name="member_password2" class="lr-input" type="password" placeholder="确认密码">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<a class="btn btn-primary" href="javascript:checkregister();">下一步</a>
					<span>
						<input id="agreement" name="agreement" type="checkbox" checked="checked">
						<a class="graylink" href="index.php?act=register&op=agreement" target="_blank">我同意养老到家服务协议</a>
						<div class="Validform-checktip Validform-wrong"></div>
					</span>
				</td>
			</tr>
		</table>
        <table class="register-form" id="login_box" style="display:none;">
			<tr>
				<th>手机号码</th>
				<td>
					<input id="login_phone" name="login_phone" class="lr-input" type="text" placeholder="手机号">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>密码</th>
				<td>
					<input id="login_password" name="login_password" class="lr-input" type="password" placeholder="密码">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<a class="btn btn-primary" href="javascript:checklogin();">登录</a>
				</td>
			</tr>
		</table>
		<div class="register-right">
			<div class="qrcode-cont">
				<img src="<?php echo $this->setting['app_image'];?>">
				<p>下载手机客户端</p>
			</div>
		</div>
	</div>
    <script type="text/javascript" src="templates/js/member/pension.js"></script>
<?php include(template('common_footer'));?>