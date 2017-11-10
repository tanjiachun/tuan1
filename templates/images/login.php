<?php include(template('common_header'));?>
	<div class="register-hd">
		<h2>
			<a href="../index.php">
				<img src="templates/images/logo.png">
			</a>
		</h2>
		<span class="r-span">还未注册，现在就&nbsp;<a href="../index.php?act=register" class="btn btn-primary">注册</a></span>
	</div>
	<div class="register-c">
		<div class="login-banner"><img src="data/login_logo.jpg"></div>
		<div class="login-form">
			<ul>
				<div class="Validform-checktip Validform-wrong login-tip"></div>
				<h6>欢迎登录</h6>
				<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
				<input type="hidden" id="refer" name="refer" value="<?php echo $refer;?>"  />
				<li>
					<div class="login-input">
						<i class="iconfont icon-user"></i>
						<input id="member_phone" name="member_phone" class="lr-input" type="text" placeholder="手机号">
					</div>
				</li>
				<li>
					<div class="login-input">
						<i class="iconfont icon-password"></i>
						<input id="member_password" name="member_password" class="lr-input" type="password" placeholder="密码">
					</div>
				</li>
				<li>
					<a class="btn btn-primary" href="javascript:checklogin();">&nbsp;&nbsp;登&nbsp;&nbsp;录&nbsp;&nbsp;</a>
				</li>
				<li class="last-li">
					<label><input type="checkbox" id="cookietime" name="cookietime" value="1">记住用户名</label>
					<span>
						<a class="graylink" href="../index.php?act=forget">忘记密码</a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a class="bluelink" href="../index.php?act=register">免费注册</a>
					</span>
				</li>
			</ul>
		</div>
	</div>
    <script type="text/javascript" src="templates/js/member/login.js"></script>
<?php include(template('common_footer'));?>