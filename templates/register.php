<?php include(template('common_header'));?>
    <style>
        .register-hd{width:1180px;height:120px;border-bottom: 1px solid #ddd;padding-bottom: 20px;margin:0 auto;margin-top: 20px;}
        .register-form .code-input .lr-input {
            width: 60%;
            vertical-align: middle;
        }
        .register-form{margin:0 auto;}
        .take-code{position: absolute;right:208px;}
        .code-input .take-code {
            cursor: default;
            display: inline-block;
            border-radius: 3px;
            vertical-align: middle;
            height: 35px;
            color: #ff6905;
            line-height: 35px;
            text-align: center;
            width: 100px;
            font-size: 12px;
            border: none;
        }
    </style>
	<div class="register-hd">
			<a href="index.php">
				<img src="templates/images/logo.png">
			</a>
        <span style="font-size: 18px;font-weight: 700;margin-left: 5px;">注册</span>
        <div style="overflow: hidden;position: absolute;bottom:0;">
            <div id="agreen_box" class="check left" style="margin-right:5px;margin-left:200px; ">
                <input class="checkbox" type="checkbox" id="agreen" style="display: none;" checked>
                <label for="agreen"></label>
                <div class="Validform-checktip Validform-wrong"></div>
            </div>
            <div class="left" style="margin-top: 3px;">
                点击本页&lt;确定&gt;，即表示同意<a href="index.php?act=article&article_id=3">《团家政使用声明》</a>
            </div>
        </div>
	</div>
	<div class="register-c">
        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
		<input type="hidden" id="next_step" name="next_step" value="<?php echo $next_step;?>" />
		<table class="register-form">
			<tr>
				<th>手机号码</th>
				<td>
					<input id="member_phone" name="member_phone" class="lr-input" type="text" placeholder="请输入手机号" maxlength="11">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>验证码</th>
				<td class="code-input" style="position:relative;">
					<input id="phone_code" name="phone_code" class="lr-input code-input" type="text" maxlength="6">
					<span class="take-code">获取手机验证码</span>
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
            <tr>
                <th>密码</th>
                <td>
                    <input id="member_pwd" name="member_pwd" class="lr-input" type="password" placeholder="请输入密码" maxlength="16">
                    <div class="Validform-checktip Validform-wrong"></div>
                </td>
            </tr>
            <tr>
                <th>确认密码</th>
                <td>
                    <input id="member_pwd2" name="member_pwd2" class="lr-input" type="password" placeholder="确认密码">
                    <div class="Validform-checktip Validform-wrong"></div>
                </td>
            </tr>
			<tr>
				<th></th>
				<td>
					<a style="width:30%;" class="btn btn-primary" href="javascript:checkregister();">确认</a>
				</td>
			</tr>
            <tr>
                <th></th>
                <td>
                    已有账号，点我直接<a style="color: #ff6905;" href="index.php?act=login">登录</a>
                </td>
            </tr>
		</table>

	</div>
<div class="modal-wrap w-400" id="alert-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
                <span class="tip-icon"></span>
                <h3 class="tip-title"></h3>
                <div class="tip-hint"></div>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" onclick="Custombox.close();">关闭</a>
    </div>
</div>
    <script type="text/javascript" src="templates/js/member/register.js"></script>
