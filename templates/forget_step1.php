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
</div>
	<div class="register-c zhmm-box">

        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
		<div class="zh-title">
			<ul>
				<li class="active"><span>1</span><br>验证信息</li>
				<li><u></u></li>
				<li><span>2</span><br>重置密码</li>
				<li><u></u></li>
				<li><span>√</span><br>　完成</li>
			</ul>
		</div>
		<table class="register-form">
			<tbody>
				<tr>
					<th>手机号码</th>
					<td>
						<input id="member_phone" name="member_phone" class="lr-input" type="text" placeholder="请输入手机号(必填)" maxlength="11">
						<div class="Validform-checktip Validform-wrong"></div>
					</td>
				</tr>
				<tr>
					<th>验证码</th>
					<td class="code-input">
						<input id="phone_code" name="phone_code" class="lr-input code-input" type="text" maxlength="6">
						<span class="take-code">获取验证码</span>
						<div class="Validform-checktip Validform-wrong"></div>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<a href="javascript:" class="btn btn-primary" onclick="checkforget()">提交</a>
					</td>
				</tr>
                <tr>
                    <th></th>
                    <td>已有账号，点我直接<a style="color:#ff6905;" href="index.php?act=login">登录</a></td>
                </tr>
			</tbody>
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
    <script type="text/javascript" src="templates/js/member/forget.js"></script>