<?php include(template('common_header'));?>
    <style>
        .register-hd{width:1180px;margin:0 auto;margin-bottom: 0;}
        .online_kefu{display: inline-block;width:18px;height:16px;background: url(../templates/images/online_kefu.png) no-repeat center;margin:0 5px 0 10px;}
        .login_bg{width:100%;min-height: 600px;background: url(../templates/images/login_bg.jpg) no-repeat center;}
        .login-form{position:absolute;background: #fff;z-index: 9999;right:50px;height:280px;padding-top:30px;top:130px;padding}
        .register-hd .r-span {
            position: absolute;
            right: 80px;
            top: 80%;
            margin-top: -18px;
        }
        .login-form {
            width: 354px;
            float: right;
        }
        .login-form h6{font-size: 16px;font-weight: 700;}
        .login-form ul {
            margin: 0 30px;
            position: relative;
        }
    </style>
<div class="register-hd">
        <a href="index.php">
            <img src="templates/images/logo.png" alt="">
        </a>
<!--    <span class="r-span"><a href="index.php?act=user_feed_back">"登录注册页面"改进意见</a><a href="javascript:;"><i class="online_kefu"></i>在线客服</a></span>-->
</div>
</div>
<div class="login_bg">

    <div style="width:1180px;margin: 0 auto;overflow: hidden;position: relative;min-height:600px;">
            <div class="login-form">
                <ul>
                    <div class="Validform-checktip Validform-wrong login-tip"></div>
                    <h6>登录团家政账户</h6>
                    <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                    <input type="hidden" id="refer" name="refer" value="<?php echo $refer;?>"  />
                    <li>
                        <div class="login-input">
                            <i class="iconfont icon-user"></i>
                            <input id="member_phone" name="member_phone" class="lr-input" type="text" placeholder="手机号" value="<?php echo $member_phone ?>">
                        </div>
                    </li>
                    <li>
                        <div class="login-input">
                            <i class="iconfont icon-password"></i>
                            <input id="member_password" name="member_password" class="lr-input" type="password" placeholder="密码">
                        </div>
                    </li>
                    <li>
                        <a class="btn btn-primary" id="login" href="javascript:checklogin();">&nbsp;&nbsp;登&nbsp;&nbsp;录&nbsp;&nbsp;</a>
                    </li>
                    <li class="last-li">
                        <label><input type="checkbox" id="cookietime" name="cookietime" value="1">记住用户名</label>
                        <span>
                            <a class="graylink" href="index.php?act=forget">忘记密码？</a>
                            <a class="bluelink" href="index.php?act=login_code">点我用验证码登录</a>
                        </span>
                    </li>
                    <li style="margin-top:30px;">
                        <span class="right"><a href="index.php?act=register">新用户注册</a></span>
                    </li>
                </ul>
            </div>
    </div>
</div>

<div style="width:1180px;margin:0 auto;border-top:1px solid #ddd;text-align: center;margin-top: 60px;">
    <img style="margin:0 5px 2px 0;" src="templates/images/heart.png" alt="">小贴士：下载手机APP团家政进行实名认证可赚取团豆豆
</div>
<script src="templates/3rd/jquery-1.11.3.min.js"></script>
<script src="templates/im/js/config.js"></script>
<script src="templates/im/js/md5.js"></script>
<script src="templates/im/js/util.js"></script>
<script type="text/javascript" src="templates/js/member/login.js"></script>
<script>
    $(function(){
        document.onkeydown = function(e){
            var ev = document.all ? window.event : e;
            if(ev.keyCode==13) {
                checklogin();
            }
        }
    });
</script>