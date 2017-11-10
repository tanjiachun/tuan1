<?php include(template('common_header'));?>
    <div class="register-hd">
        <h2>
            <a href="index.php">
                <img src="templates/images/logo.png">
            </a>
        </h2>
        <span class="r-span">我已注册，现在就&nbsp;<a href="index.php?act=login" class="btn btn-primary">登录</a></span>
    </div>
    <div class="register-c">
        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
        <input type="hidden" id="next_step" name="next_step" value="<?php echo $next_step;?>" />
        <table class="register-form">
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
                    <a class="btn btn-primary" href="javascript:checkregister();">同意协议并注册</a>
                    <span>
						<input id="agreement" name="agreement" type="checkbox" checked="checked">
						<a class="graylink" href="index.php?act=register&op=agreement" target="_blank">我同意养老到家服务协议</a>
						<div class="Validform-checktip Validform-wrong"></div>
					</span>
                </td>
            </tr>
        </table>
        <div class="register-right">
            <div class="register-tips">
                <h3>注册资料</h3>
                <ul>
                    <li><a href="index.php?act=register&op=privacy">养老到家隐私权条款</a></li>
                    <li><a href="index.php?act=register&op=use">养老到家使用协议</a></li>
                </ul>
            </div>
            <div class="qrcode-cont">
                <img src="<?php echo $this->setting['app_image'];?>">
                <p>下载手机客户端</p>
            </div>
            <div class="register-tel">客服专线：<?php echo $this->setting['site_phone'];?></div>
        </div>
    </div>
    <script type="text/javascript" src="templates/js/member/register.js"></script>
<?php include(template('common_footer'));?>