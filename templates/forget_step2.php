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
        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
		<div class="zh-title">
			<ul>
				<li class="active"><span>1</span><br>验证信息</li>
				<li class="active"><u></u></li>
				<li class="active"><span>2</span><br>重置密码</li>
				<li><u></u></li>
				<li><span>√</span><br>　完成</li>
			</ul>
		</div>
		<table class="register-form">
			<tbody>
				<tr>
					<th>设置新密码</th>
					<td>
						<input id="member_password" name="member_password" class="lr-input" type="text">
						<div class="Validform-checktip Validform-wrong"></div>
					</td>
				</tr>
				<tr>
					<th>确认新密码</th>
					<td>
						<input id="member_password2" name="member_password2" class="lr-input" type="text">
						<div class="Validform-checktip Validform-wrong"></div>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<a href="javascript:checkedit();" class="btn btn-primary">提交</a>
					</td>
				</tr>
                <tr>
                    <th></th>
                    <td>已有账号，点我直接<a style="color:#ff6905;" href="index.php?act=login">登录</a></td>
                </tr>
			</tbody>
		</table>
	</div>
    <script type="text/javascript" src="templates/js/member/forget.js"></script>