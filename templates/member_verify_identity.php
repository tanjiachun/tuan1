<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
    </style>
<?php //var_dump($member);?>
<link href="templates/css/bootstrap.min.css" rel="stylesheet">
<link href="templates/css/verify.css" rel="stylesheet">
<script src="templates/js/member/jquery-1.11.1.min.js"></script>
<script src="templates/js/member/verify_check.js"></script>
<link rel="stylesheet" href="templates/css/admin.css">
    <div class="member_center_header">
        <img src="templates/images/my_tjz.png" alt="">
    </div>
</div>
<div id="member_manage">
    <div id="member_manage_content">
        <div class="login-box f-mt10 f-pb50">
            <div class="main bgf">
                <div class="reg-box-pan display-inline">
                    <div class="step">
                        <ul>
                            <li class="col-xs-4 on">
                                <span class="num"><em class="f-r5"></em><i>1</i></span>
                                <span class="line_bg lbg-r"></span>
                                <p class="lbg-txt">填写个人信息</p>
                            </li>
                            <li class="col-xs-4">
                                <span class="num"><em class="f-r5"></em><i>2</i></span>
                                <span class="line_bg lbg-l"></span>
                                <span class="line_bg lbg-r"></span>
                                <p class="lbg-txt">上传个人资料</p>
                            </li>
                            <li class="col-xs-4">
                                <span class="num"><em class="f-r5"></em><i>3</i></span>
                                <span class="line_bg lbg-l"></span>
                                <p class="lbg-txt">实名成功</p>
                            </li>
                        </ul>
                    </div>
                    <div class="reg-box" id="verifyCheck" style="margin-top:20px;">
                        <div class="part1">
                            <div class="item col-xs-12">
                                <span class="intelligent-label f-fl"><b class="ftx04">*</b>真实姓名：</span>
                                <div class="f-fl item-ifo">
                                    <input type="text" maxlength="20" class="txt03 f-r3 required" tabindex="1" data-valid="isNonEmpty||between:2-20||isZh" data-error="真实姓名不能为空||请输入您的真实姓名||请输入您的真实姓名" id="personal_name" />                            <span class="ie8 icon-close close hide"></span>
                                    <label class="icon-sucessfill blank hide"></label>
                                    <label class="focus"><span>请输入您的真实姓名</span></label>
                                    <label class="focus valid"></label>
                                </div>
                            </div>
                            <div class="item col-xs-12">
                                <span class="intelligent-label f-fl"><b class="ftx04">*</b>身份证号码：</span>
                                <div class="f-fl item-ifo">
                                    <input type="text" maxlength="20" class="txt03 f-r3 required" tabindex="1" data-valid="isNonEmpty||between:18-18||isCard" data-error="身份证号码不能为空||身份证号码长度18位||只能输入数字" id="personal_number" />                            <span class="ie8 icon-close close hide"></span>
                                    <label class="icon-sucessfill blank hide"></label>
                                    <label class="focus"><span>请输入您的18位身份证号码</span></label>
                                    <label class="focus valid"></label>
                                </div>
                            </div>
                            <div class="item col-xs-12">
                                <span class="intelligent-label f-fl"><b class="ftx04">*</b>手机号：</span>
                                <div class="f-fl item-ifo">
                                    <input type="text" value="<?php print $member['member_phone'];?>" class="txt03 f-r3 required" keycodes="tel" tabindex="2" data-valid="isNonEmpty||isPhone" data-error="手机号码不能为空||手机号码格式不正确" maxlength="11" id="disabledInput" disabled />
                                    <span class="ie8 icon-close close hide"></span>
                                    <label class="icon-sucessfill blank"></label>
                                    <label class="focus hidden">请填写11位有效的手机号码</label>
                                </div>
                            </div>


                            <div class="item col-xs-12">
                                <span class="intelligent-label f-fl"><b class="ftx04">*</b>验证码：</span>
                                <div class="f-fl item-ifo">
                                    <input type="text" maxlength="6" id="verifyNo" class="txt03 f-r3 f-fl required" tabindex="4" style="width:167px" data-valid="isNonEmpty||isInt" data-error="验证码不能为空||请输入6位数字验证码" />
<!--                                    <span class="btn btn-gray f-r3 f-ml5 f-size13" id="time_box" disabled style="width:97px;display:none;">发送验证码</span>-->
<!--                                    <span class="btn btn-gray f-r3 f-ml5 f-size13" id="verifyYz" style="width:97px;">发送验证码</span>-->
                                    <style>
                                         .take-code {
                                            cursor: pointer;
                                            display: inline-block;
                                             margin-left:3px;
                                            border-radius: 3px;
                                            vertical-align: middle;
                                            height: 36px;
                                            color: #5a98de;
                                            line-height: 36px;
                                            text-align: center;
                                            width: 100px;
                                            font-size: 12px;
                                            border: 1px solid #5a98de;
                                        }
                                    </style>
                                    <span class="take-code">获取验证码</span>
                                    <span class="ie8 icon-close close hide" style="right:130px"></span>
                                    <label class="icon-sucessfill blank hide"></label>
                                    <label class="focus"><span>请查收手机短信，并填写短信中的验证码（此验证码2分钟内有效）</span></label>
                                    <label class="focus valid"></label>
                                </div>
                            </div>
                            <div class="item col-xs-12 hide" id="msg_alert">
                                <div class="alert alert-info" style="width:700px">短信已发送至您手机，请输入短信中的验证码，确保您的手机号码实名信息与提交的身份证信息吻合。</div>
                            </div>
                            <div class="item col-xs-12" style="height:auto">
                                <span class="intelligent-label f-fl">&nbsp;</span>
                                <p class="f-size14 required"  data-valid="isChecked" data-error="请先同意条款">
                                    <input type="checkbox" checked /><a href="javascript:showoutc();" class="f-ml5">我已阅读并同意条款</a>
                                </p>
                                <label class="focus valid"></label>
                            </div>
                            <div class="item col-xs-12">
                                <span class="intelligent-label f-fl">&nbsp;</span>
                                <div class="f-fl item-ifo">
                                    <a href="javascript:;" class="btn btn-blue f-r3" id="btn_part1">下一步</a>
                                </div>
                            </div>
                        </div>
                        <div class="part2" style="display:none">
                            <div class="user-info" style="height:600px;">
                                <div class="form-list">
                                    <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                                    <div class="form-item clearfix">
                                        <label style="width:150px;"><span class="red">*</span>身份证正面照：</label>
                                        <div class="picture-list">
                                            <ul class="clearfix">
                                                <li id="show_image_0" class="cover-item" style="display:none;width:200px;height:150px;"></li>
                                                <li id="upload_image_0" style="width:200px;height:150px;">
                                                    <div class="img-update">
                                                        <span class="img-layer">+ 上传</span>
                                                        <span class="img-file">
                                        <input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                    </span>
                                                    </div>
                                                </li>
                                                <input type="hidden" id="member_cardid_image" name="member_cardid_image" class="image_0" value=""  />
                                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                                            </ul>
                                            <p class="help-desc">所有图片皆为 .jpg 或 .png格式 图片内存不大于800KB</p>
                                        </div>
                                    </div>
                                    <div class="form-item clearfix">
                                        <label style="width: 150px;"><span class="red">*</span>身份证反面照：</label>
                                        <div class="picture-list">
                                            <ul class="clearfix">
                                                <li id="show_image_1" class="cover-item" style="display:none;width:200px;height:150px;"></li>
                                                <li id="upload_image_1" style="width:200px;height:150px;">
                                                    <div class="img-update">
                                                        <span class="img-layer">+ 上传</span>
                                                        <span class="img-file">
                                        <input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                    </span>
                                                    </div>
                                                </li>
                                                <input type="hidden" id="member_cardid_back_image" name="member_cardid_back_image" class="image_1" value=""  />
                                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                                            </ul>
                                            <p class="help-desc"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item col-xs-12">
                                <span class="intelligent-label f-fl">&nbsp;</span>
                                <div class="f-fl item-ifo">
                                    <a href="javascript:;" class="btn btn-blue f-r3" id="btn_part2">认证</a>
                                </div>
                            </div>
                        </div>

                        <div class="part3 text-center" style="display:none">

                            <h3>恭喜您，您的实名信息已经完善！</h3>
                            <p class="c-666 f-mt30 f-mb50"><a href="./index.php?act=member_center" style="color:#0080cb;">页面将在 <strong id="times" class="f-size18">3</strong> 秒钟后，跳转到 用户中心</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-sPopBg" style="z-index:998;"></div>
        <div class="m-sPopCon regcon">
            <div class="m-sPopTitle"><strong>服务协议条款</strong><b id="sPopClose" class="m-sPopClose" onClick="closeClause()">×</b></div>
            <div class="apply_up_content">
    	<pre class="f-r0">
		<strong>同意以下服务条款，提交注册信息</strong>
        </pre>
            </div>
            <center><a class="btn btn-blue btn-lg f-size12 b-b0 b-l0 b-t0 b-r0 f-pl50 f-pr50 f-r3" href="javascript:closeClause();">已阅读并同意此条款</a></center>
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
        <script type="text/javascript">
            var file_name = 'plat';
        </script>
        <script>
            var code_btn = false;
            $('.take-code').on('click', function() {
                if($('.take-code').hasClass('acquired')) return;
                var member_phone = $('#disabledInput').val();
                if(member_phone == '') {
                    showerror('member_phone', '手机号必须填写');
                    return;
                }
                var regu = /^[1][0-9]{10}$/;
                if(!regu.test(member_phone)) {
                    showerror('member_phone', '手机号格式不正确');
                    return;
                }
                if(code_btn) return;
                code_btn = true;
                var submitData = {
                    'member_phone' : member_phone
                };
                $.ajax({
                    type : 'POST',
                    url : 'index.php?act=misc&op=member_verify_code',
                    data : submitData,
                    contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                    dataType : 'json',
                    success : function(data){
                        code_btn = false;
                        if(data.done == 'true') {
                            var second = 60;
                            $('.take-code').addClass('acquired');
                            $('.take-code').html('重新获取('+ second +'秒)');
                            var progress = setInterval(function(){
                                if(second <= 0) {
                                    $('.take-code').removeClass('acquired');
                                    $('.take-code').html('重新获取');
                                    clearInterval(progress);
                                } else {
                                    second--;
                                    $('.take-code').html('重新获取('+ second +'秒)');
                                }
                            },1000);
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
                    timeout : 15000,
                    error : function(xhr, type){
                        code_btn = false;
                        showalert('网路不稳定，请稍候重试');
                    }
                });
            });
        </script>
        <script>
            function lazyGo() {
                var sec = $("#times").text();
                $("#times").text(--sec);
                if (sec > 0){
                    setTimeout("lazyGo();", 1000);
                }
                else{
                    window.location.href = './index.php?act=member_center';
                }
            }

            $(function(){
                //第一页的确定按钮
                $("#btn_part1").click(function(){
                    var member_truename=$("#personal_name").val();
                    var member_cardid=$("#personal_number").val();
                    var member_phone=$("#disabledInput").val();
                    var phone_code=$("#verifyNo").val();
                    if(member_truename==''|| member_cardid=='' || member_phone=='' || phone_code=='') {
                        showalert("请正确填写验证资料！");
                        return;
                    }

                    var submitData={
                        'member_truename':member_truename,
                        'member_cardid':member_cardid,
                        'member_phone':member_phone,
                        'phone_code':phone_code
                    };
                     $.ajax({
                         type : 'POST',
                         url : 'index.php?act=member_verify_identity&op=real_name_step1',
                         data : submitData,
                         contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                         dataType : 'json',
                         success:function (data) {
                             console.log(data);
                            if(data.done=='true'){
                                $(".part1").hide();
                                $(".part2").show();
                                $(".step li").eq(1).addClass("on");
                            }else{
                                showalert(data.msg);
                            }
                         },
                         timeout:15000,
                         error:function (xhr, type) {
                             showalert("网络不稳定，请稍后重试");
                         }
                     });
//                    if(!verifyCheck._click()) return;
//                    $(".part1").hide();
//                    $(".part2").show();
//                    $(".step li").eq(1).addClass("on");
                });
                //第二页的确定按钮
                $("#btn_part2").click(function(){
                    var member_cardid_image=$("#member_cardid_image").val();
                    var member_cardid_back_image=$("#member_cardid_back_image").val();
                    if(member_cardid_image=='' || member_cardid_back_image=='') {
                        showalert("请先上传所有文件！");
                        return;
                    }
                    var submitData={
                        'member_cardid_image':member_cardid_image,
                        'member_cardid_back_image':member_cardid_back_image
                    };
                    console.log(submitData);
                    $.ajax({
                        type : 'POST',
                        url : 'index.php?act=member_verify_identity&op=real_name_step2',
                        data : submitData,
                        contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                        dataType : 'json',
                        success:function (data) {
                            console.log(data);
                            if(data.done=='true'){
                                $(".part2").hide();
                                $(".part3").show();
                                $(".step li").eq(2).addClass("on");
                                $(function () {
                                    setTimeout("lazyGo();", 1000);
                                });

                            }else{
                                showalert(data.msg);
                            }
                        },
                        timeout:15000,
                        error:function (xhr, type) {
                            showalert("网络不稳定，请稍后重试");
                        }
                    });
//                    if(!verifyCheck._click()) return;
//                    $(".part2").hide();
//                    $(".part3").show();
                });
                //第三页的确定按钮
                $("#btn_part3").click(function(){
                    if(!verifyCheck._click()) return;
                    $(".part3").hide();
                    $(".part4").show();
                    $(".step li").eq(2).addClass("on");
                    countdown({
                        maxTime:10,
                        ing:function(c){
                            $("#times").text(c);
                        },
                        after:function(){
                            window.location.href="my.html";
                        }
                    });
                });
            });
            function showoutc(){$(".m-sPopBg,.m-sPopCon").show();}
            function closeClause(){
                $(".m-sPopBg,.m-sPopCon").hide();
            }
        </script>
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

<script type="text/javascript">
    var file_name = 'plat';
</script>
<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="templates/js/imageupload.js"></script>
<script>
// $(".member_real_submit_btn").click(function () {
//     var formhash = $('#formhash').val();
//     var member_truename=$("#member_truename").val();
//     var member_cardid_image=$("#member_cardid_image").val();
//     var member_cardid_person_image=$("#member_cardid_person_image").val();
//     if(member_truename==''){
//         $("#member_truename").focus();
//         showerror('member_truename','请填写真实姓名');
//         return;
//     }else{
//         showsuccess('member_truename');
//     }
//     if(member_cardid_image==''){
//         $("#member_cardid_image").focus();
//         showerror('member_cardid_image','请上传身份证正面照');
//     }else{
//         showsuccess('member_cardid_image');
//     }
//     if(member_cardid_back_image==''){
//         $("#member_cardid_back_image").focus();
//         showerror('member_cardid_back_image','请上传身份证反面照');
//     }else{
//         showsuccess('member_cardid_back_image');
//     }
//     if(member_cardid_person_image==''){
//         $("#member_cardid_person_image").focus();
//         showerror('member_cardid_person_image','请上传手持身份证照');
//     }else{
//         showsuccess('member_cardid_person_image');
//     }
//     var submitData={
//         'form_submit' : 'ok',
//         'formhash' : formhash,
//         'member_truename':member_truename,
//         'member_cardid_image':member_cardid_image,
//         'member_cardid_back_image':member_cardid_back_image,
//         'member_cardid_person_image':member_cardid_person_image
//     };
//     $.ajax({
//         type : 'POST',
//         url : 'index.php?act=member_real_name&op=real_name',
//         data : submitData,
//         contentType: 'application/x-www-form-urlencoded; charset=utf-8',
//         dataType : 'json',
//         success:function (data) {
//             console.log(data);
//            if(data.done=='true'){
//                showalert('提交成功');
//            }else{
//                showalert(data.msg);
//            }
//         },
//         timeout:15000,
//         error:function (xhr, type) {
//             showalert("网络不稳定，请稍后重试");
//         }
//     });
// });
</script>