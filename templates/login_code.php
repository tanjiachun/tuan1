<?php include(template('common_header'));?>
<style>
    .register-hd{width:1180px;border-bottom: 1px solid #ddd;padding-bottom: 20px;margin:0 auto;margin-top: 20px;}
    .register-form .code-input .lr-input {
        width: 216px;
        vertical-align: middle;
    }
    .take-code{position: absolute;right:208px;}
    .register-form .code-input .lr-input {
        width: 60%;
        vertical-align: middle;
    }
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
    <span style="font-size: 16px;font-weight: 700;margin-left: 5px;">验证码登录</span>
</div>
</div>
<div class="register-c zhmm-box">
        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
        <input type="hidden" id="refer" name="refer" value="<?php echo $refer;?>"  />
        <table class="register-form">
            <tbody>
            <tr>
                <th>手机号码</th>
                <td>
                    <input id="member_phone" name="member_phone" class="lr-input" type="text" placeholder="请输入手机号(必填)">
                    <div class="Validform-checktip Validform-wrong"></div>
                </td>
            </tr>
            <tr>
                <th>验证码</th>
                <td class="code-input" style="position: relative;">
                    <input id="phone_code" name="phone_code" class="lr-input code-input" type="text">
                    <span class="take-code">获取手机验证码</span>
                    <div class="Validform-checktip Validform-wrong"></div>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <a href="javascript:checkforget();" class="btn btn-primary">提交</a>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    已有账号，点我直接<a style="color: #ff6905;" href="index.php?act=login">登录</a>
                </td>
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
<script>
    var submit_btn = false;
    function checkforget() {
        var formhash = $('#formhash').val();
        var refer = $('#refer').val();
        var member_phone = $('#member_phone').val();
        var phone_code = $('#phone_code').val();
        if(member_phone == '') {
            showerror('member_phone', '手机号必须填写');
            return;
        }
        var regu = /^[1][0-9]{10}$/;
        if(!regu.test(member_phone)) {
            showerror('member_phone', '手机号格式不正确');
            return;
        }
        if(phone_code == '') {
            showerror('phone_code', '验证码必须填写');
            return;
        }
        var submitData = {
            'form_submit' : 'ok',
            'formhash' : formhash,
            'member_phone' : member_phone,
            'phone_code' : phone_code
        };
        if(submit_btn) return;
        submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=login_code',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data) {
                console.log(data);
                submit_btn = false;
                if(data.done == 'true') {
                    window.location.href = 'index.php';
                } else if(data.done == 'login') {
                    window.location.href = refer;
                } else {
                    showalert( data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                submit_btn = false;
                showalert('网路不稳定，请稍候重试');
            }
        });
    }
    $(function() {
        var code_btn = false;
        $('.take-code').on('click', function () {
            showsuccess('member_phone');
            if ($('.take-code').hasClass('acquired')) return;
            var member_phone = $('#member_phone').val();
            if (member_phone == '') {
                showerror('member_phone', '手机号必须填写');
                return;
            }
            var regu = /^[1][0-9]{10}$/;
            if (!regu.test(member_phone)) {
                showerror('member_phone', '手机号格式不正确');
                return;
            }
            if (code_btn) return;
            code_btn = true;
            var submitData = {
                'member_phone': member_phone
            };
            $.ajax({
                type: 'POST',
                url: 'index.php?act=misc&op=login_code',
                data: submitData,
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                dataType: 'json',
                success: function (data) {
                    code_btn = false;
                    if (data.done == 'true') {
                        var second = 60;
                        $('.take-code').addClass('acquired');
                        $('.take-code').html('重新获取(' + second + '秒)');
                        var progress = setInterval(function () {
                            if (second <= 0) {
                                $('.take-code').removeClass('acquired');
                                $('.take-code').html('重新获取');
                                clearInterval(progress);
                            } else {
                                second--;
                                $('.take-code').html('重新获取(' + second + '秒)');
                            }
                        }, 1000);
                    } else {
                        showerror('member_phone', data.msg);
                        if (data.time > 0){
                            var second = data.time;
                            $('.take-code').addClass('acquired');
                            $('.take-code').html('重新获取(' + second + '秒)');
                            var progress = setInterval(function () {
                                if (second <= 0) {
                                    $('.take-code').removeClass('acquired');
                                    $('.take-code').html('重新获取');
                                    clearInterval(progress);
                                } else {
                                    second--;
                                    $('.take-code').html('重新获取(' + second + '秒)');
                                }
                            }, 1000);
                        }
                    }
                },
                timeout: 15000,
                error: function (xhr, type) {
                    code_btn = false;
                    showalert('网路不稳定，请稍候重试');
                }
            });
        });

        $('#member_phone').on('blur', function () {
            var member_phone = $('#member_phone').val();
            if (member_phone == '') {
                showerror('member_phone', '手机号必须填写');
                return;
            }
            var regu = /^[1][0-9]{10}$/;
            if (!regu.test(member_phone)) {
                showerror('member_phone', '手机号格式不正确');
                return;
            }
            $.getJSON('index.php?act=login_code&op=checkname', {'member_phone': member_phone}, function (data) {
                if (data.done == 'true') {
                    showsuccess('member_phone');
                } else {
                    showerror('member_phone', data.msg);
                }
            });
        });

        $('#phone_code').on('blur', function () {
            var phone_code = $('#phone_code').val();
            if (phone_code != '') {
                showsuccess('phone_code');
            }
        });
    });
</script>
