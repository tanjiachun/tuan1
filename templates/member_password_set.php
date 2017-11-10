<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
        .take-code{
            border:1px solid #ccc;
            padding:5px 10px;
            cursor: pointer;
        }
        .take-code:hover{
            color: #ff6905;
        }
        #login_phone_code,#pay_phone_code{
            height:27px;
        }
    </style>
    <link rel="stylesheet" href="templates/css/admin.css">
    <div class="member_center_header">
        <img src="templates/images/my_tjz.png" alt="">
    </div>
</div>
<div id="member_manage">
    <div id="member_manage_content">
        <div id="member_manage_sidebar" class="left">
            <div class="member_manage_image">
                <?php if(empty($this->member['member_avatar'])) { ?>
                    <img width="100px" height="100px" src="templates/images/peopleicon_01.gif">
                <?php } else { ?>
                    <img width="100px" height="100px" src="<?php echo $this->member['member_avatar'];?>">
                <?php } ?>
            </div>
            <ul class="member_sidebar_list">
                <li class="staff_set_list">
                    <a class="list_show">账户与安全</a>
                    <ul style="display: none;">
                        <li><a href="index.php?act=member_center" style="font-size: 12px;">· 个人资料</a></li>
                        <li><a href="index.php?act=member_real_name" style="font-size: 12px;">· 实名认证</a></li>
                        <li><a href="index.php?act=member_address_set" style="font-size: 12px;">· 地址管理</a></li>
                    </ul>
                </li>
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=member_password_set" >密码管理</a></li>
                <li><a href="index.php?act=member_book">我的订单</a></li>
                <li><a href="index.php?act=member_wallet">我的钱包</a></li>
                <li><a href="index.php?act=member_comment">我的评价</a></li>
                <li><a href="index.php?act=favorite&op=nurse">我的关注</a></li>
            </ul>
            <script>
                $(".list_show").click(function () {
                    if(!$(".staff_set_list ul").is(":hidden")){
                        $(".staff_set_list ul").fadeOut();
                        $(".staff_set_list img").attr('src','templates/images/toBW.png');
                    }else{
                        $(".staff_set_list ul").fadeIn();
                        $(".staff_set_list img").attr('src','templates/images/toTopW.png');
                    }
                })
            </script>
        </div>
        <div id="member_manage_set" class="left">
            <div class="member_login_password_set">
                <p><span>登录密码修改</span></p>
                <div class="user-right">
                    <div class="user-info">
                        <div class="form-list">
                            <div class="form-item clearfix">
                                <label>手机号：</label>
                                <div class="form-item-value">
                                    <?php echo $this->member['member_phone'];?>
                                    <span style="color:#ff6905;cursor: pointer" class="member_phone_resume">[手机不可用 ? 点击 <a href="index.php?act=member_center"">此处</a>修改]</span>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>验证码：</label>
                                <div class="form-item-value">
                                    <input id="login_phone_code" name="login_phone_code" class="lr-input code-input" type="text" maxlength="6">
                                    <span class="login_code take-code">获取验证码</span>
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>新密码：</label>
                                <div class="form-item-value">
                                    <input maxlength="16" type="password" id="login_password" name="login_password" value="" class="form-input" placeholder="输入新密码">
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>确认新密码：</label>
                                <div class="form-item-value">
                                    <input maxlength="16" type="password" id="login_password2" name="login_password2" value="" class="form-input" placeholder="确认新密码">
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label></label>
                                <div class="form-item-value">
                                    <span class="login_password_btn">确定修改</span>
                                    <span class="return-success"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="member_pay_password_set">
                <p><span>支付密码修改</span></p>
                <div class="user-right">
                    <div class="user-info">
                        <div class="form-list">
                            <div class="form-item clearfix">
                                <label>手机号：</label>
                                <div class="form-item-value">
                                    <?php echo $this->member['member_phone'];?>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>验证码：</label>
                                <div class="form-item-value">
                                    <input id="pay_phone_code" name="pay_phone_code" class="lr-input code-input" type="text">
                                    <span class="pay_code take-code">获取验证码</span>
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>新密码：</label>
                                <div class="form-item-value">
                                    <input type="password" id="pay_password" name="pay_password" value="" class="form-input" placeholder="输入新密码">
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>确认新密码：</label>
                                <div class="form-item-value">
                                    <input type="password" id="pay_password2" name="pay_password2" value="" class="form-input" placeholder="确认新密码">
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label></label>
                                <div class="form-item-value">
                                    <span class="pay_password_btn">确定修改</span>
                                    <span class="return_success" style="color: #ff6905;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

<script>
    var member_phone='<?php echo $this->member['member_phone'];?>';
</script>
<script>
    $('.login_code').on('click', function() {

        var submitData = {
            'member_phone' : member_phone
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=misc&op=login_pwd_code',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                code_btn = false;
                if(data.done == 'true') {
                    var second = 60;
                    $('.login_code').addClass('acquired');
                    $('.login_code').html('重新获取('+ second +'秒)');
                    var progress = setInterval(function(){
                        if(second <= 0) {
                            $('.login_code').removeClass('acquired');
                            $('.login_code').html('重新获取');
                            clearInterval(progress);
                        } else {
                            second--;
                            $('.login_code').html('重新获取('+ second +'秒)');
                        }
                    },1000);
                } else {
                    showalert(data.msg);
                    if (data.time > 0){
                        var second = data.time;
                        $('.login_code').addClass('acquired');
                        $('.login_code').html('重新获取(' + second + '秒)');
                        var progress = setInterval(function () {
                            if (second <= 0) {
                                $('.login_code').removeClass('acquired');
                                $('.login_code').html('重新获取');
                                clearInterval(progress);
                            } else {
                                second--;
                                $('.login_code').html('重新获取(' + second + '秒)');
                            }
                        }, 1000);
                    }
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showalert('网路不稳定，请稍候重试');
            }
        });
    });
    $(".login_password_btn").click(function () {
        var login_phone_code=$("#login_phone_code").val();
        var login_password=$("#login_password").val();
        var login_password2=$("#login_password2").val();
        if(login_phone_code==''){
            showerror('login_phone_code','验证码必须填写');
            return;
        }else {
            showsuccess('login_phone_code');
        }
        if(login_password==''){
            showerror('login_password','密码必须填写');
            return;
        }else {
            showsuccess('login_password');
        }
        if(login_password.length < 9){
            showerror('login_password','密码不能小于9位');
            return;
        }else{
            showsuccess('login_password');
        }
        var reg = new RegExp("\\s");
        if(login_password.substr(0).match(reg)!=null){
            showerror('login_password','密码不能存在空格');
            return;
        }else{
            showsuccess('login_password');
        }
        if(login_password2==''){
            showerror('login_password2','密码必须填写');
        }else {
            showsuccess('login_password2');
        }
        if(login_password2!==login_password){
            showerror('login_password2','两次输入必须一样');
            return;
        }else{
            showsuccess('login_password2');
        }
        var submitData={
            'member_phone':member_phone,
            'login_phone_code':login_phone_code,
            'login_password':login_password,
            'login_password2':login_password2
        };
        console.log(submitData);
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_password_set&op=login_set',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data) {
                submit_btn = false;
                if(data.done == 'true') {
                    $('.return-success').html('修改成功');
                    $('.return-success').show();
                    setTimeout(function(){
                        window.location.href = 'index.php?act=member_password_set';
                    }, 1000);
                } else {
                    showalert(data.msg)
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showalert('网路不稳定，请稍候重试');
            }
        });
    });
</script>
<script>
    $('.pay_code').on('click', function() {
        var submitData = {
            'member_phone' : member_phone
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=misc&op=pay_pwd_code',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                code_btn = false;
                if(data.done == 'true') {
                    var second = 60;
                    $('.pay_code').addClass('acquired');
                    $('.pay_code').html('重新获取('+ second +'秒)');
                    var progress = setInterval(function(){
                        if(second <= 0) {
                            $('.pay_code').removeClass('acquired');
                            $('.pay_code').html('重新获取');
                            clearInterval(progress);
                        } else {
                            second--;
                            $('.pay_code').html('重新获取('+ second +'秒)');
                        }
                    },1000);
                } else {
                    showalert(data.msg)
                    if (data.time > 0){
                        var second = data.time;
                        $('.pay_code').addClass('acquired');
                        $('.pay_code').html('重新获取(' + second + '秒)');
                        var progress = setInterval(function () {
                            if (second <= 0) {
                                $('.pay_code').removeClass('acquired');
                                $('.pay_code').html('重新获取');
                                clearInterval(progress);
                            } else {
                                second--;
                                $('.pay_code').html('重新获取(' + second + '秒)');
                            }
                        }, 1000);
                    }
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showalert('网路不稳定，请稍候重试');
            }
        });
    });
    $(".pay_password_btn").click(function () {
        var pay_phone_code=$("#pay_phone_code").val();
        var pay_password=$("#pay_password").val();
        var pay_password2=$("#pay_password2").val();
        if(pay_phone_code==''){
            showerror('pay_phone_code','验证码必须填写');
            return;
        }else {
            showsuccess('pay_phone_code');
        }
        if(pay_password==''){
            showerror('pay_password','密码必须填写');
            return;
        }else {
            showsuccess('pay_password');
        }
        if(pay_password.length < 9){
            showerror('pay_password','密码不能小于9位');
            return;
        }else{
            showsuccess('pay_password');
        }
        var reg = new RegExp("\\s");
        if(pay_password.substr(0).match(reg)!=null){
            showerror('pay_password','密码不能存在空格');
            return;
        }else{
            showsuccess('pay_password');
        }
        if(pay_password2==''){
            showerror('pay_password2','密码必须填写');
        }else {
            showsuccess('pay_password2');
        }
        if(pay_password2!==pay_password){
            showerror('pay_password2','两次输入必须一样');
            return;
        }else{
            showsuccess('pay_password2');
        }
        var submitData={
            'member_phone':member_phone,
            'pay_phone_code':pay_phone_code,
            'pay_password':pay_password,
            'pay_password2':pay_password2
        };
        console.log(submitData);
        $.ajax({
            type: 'POST',
            url: 'index.php?act=member_password_set&op=pay_set',
            data: submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType: 'json',
            success: function (data) {
                submit_btn = false;
                if (data.done == 'true') {
                    $('.return_success').html('修改成功');
                    setTimeout(function () {
                        window.location.href = 'index.php?act=member_password_set';
                    }, 1000);
                } else {
                    showalert(data.msg)
                }
            },
            timeout: 15000,
            error: function (xhr, type) {
                showalert('网路不稳定，请稍候重试');
            }
        });
    });
</script>