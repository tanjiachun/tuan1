<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
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
                    <ul>
                        <li><a href="index.php?act=member_center" style="font-size: 12px;">· 个人资料</a></li>
                        <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=member_real_name" style="font-size: 12px;">· 实名认证</a></li>
                        <li><a href="index.php?act=member_address_set" style="font-size: 12px;">· 地址管理</a></li>
                    </ul>
                </li>
                <li><a href="index.php?act=member_password_set" >密码管理</a></li>
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
            <div class="member_real_name_set">
                <p><span>相关认证</span></p>
                <h3 style="margin-left: 10px;color:#ff6905;">亲爱的 <?php print $member_name;?> , 请填写真实详细的资料</h3>
                <div class="user-right">
                    <div class="user-info">
                            <div id="verify_list" style="margin:50px 0 0 20px;">
                                <div class="real_name" style="height:100px;vertical-align:middle;overflow: hidden;border-bottom: 1px solid #ddd;">
                                    <div class="left" style="width:20%;text-align: center;margin-top: 10px;">
                                        <img src="templates/images/real_name.png" alt="" width="50px;"> <br>
                                        <span style="font-size: 16px;">实名认证</span>
                                    </div>
                                    <div class="left" style="width:20%;text-align: center;line-height: 100px;">
                                        <?php if(empty($this->member['member_real_state'])) { ?>
                                            <img src="templates/images/real_cha.png" alt="">
                                            <span>未认证</span>
                                        <?php } else { ?>
                                            <img src="templates/images/real_gou.png" alt="">
                                            <span>已认证</span>
                                        <?php } ?>
                                    </div>
                                        <?php if(empty($this->member['member_real_state'])) { ?>
                                    <div class="left" style="width:40%;text-align: center;word-wrap: break-word;word-break:break-all;margin-top: 30px;">
                                            为了保护您账号的安全，维护您的权益，建立完善可靠的互联网信用，建议您尽快进行实名认证
                                    </div>
                                        <?php } else { ?>
                                    <div class="left" style="width:40%;text-align: center;word-wrap: break-word;word-break:break-all;margin-top: 40px;">
                                            您已经通过实名认证
                                    </div>
                                        <?php } ?>
                                    <div class="left" style="width: 20%;text-align: center;line-height: 100px;">
                                        <?php if(empty($this->member['member_real_state'])) { ?>
                                            <a href="index.php?act=member_verify_identity" style="color:#ff6905;">马上认证</a>
                                        <?php } else if($this->member['member_real_state']==1) { ?>
                                            <span style="font-weight:300;">已认证</span>
                                        <?php } else if($this->member['member_real_state']==2) { ?>
                                            <a href="index.php?act=member_verify_identity" style="color:#ff6905;">重新认证</a>
                                        <?php } else if($this->member['member_real_state']==3) { ?>
                                            <span style="font-weight:300;">认证中</span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <div id="verify_list" style="margin:0 0 0 20px;">
                            <div class="real_name" style="height:100px;vertical-align:middle;overflow: hidden;">
                                <div class="left" style="width:20%;text-align: center;margin-top: 10px;">
                                    <img src="templates/images/real_phone.png" alt="" width="50px;"> <br>
                                    <span style="font-size: 16px;">手机认证</span>
                                </div>
                                <div class="left" style="width:20%;text-align: center;line-height: 100px;">

                                        <img src="templates/images/real_gou.png" alt="">
                                        <span>已认证</span>

                                </div>
                                <div class="left" style="width:40%;text-align: center;word-wrap: break-word;word-break:break-all;margin-top: 40px;">
                                    您已经通过手机认证
                                </div>
                                <div class="left" style="width: 20%;text-align: center;line-height: 100px;">
                                    <span style="font-weight:300;">已认证</span>
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

<script type="text/javascript">
    var file_name = 'plat';
</script>
<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="templates/js/imageupload.js"></script>
<script>
 $(".member_real_submit_btn").click(function () {
     var formhash = $('#formhash').val();
     var member_truename=$("#member_truename").val();
     var member_cardid_image=$("#member_cardid_image").val();
     var member_cardid_person_image=$("#member_cardid_person_image").val();
     if(member_truename==''){
         $("#member_truename").focus();
         showerror('member_truename','请填写真实姓名');
         return;
     }else{
         showsuccess('member_truename');
     }
     if(member_cardid_image==''){
         $("#member_cardid_image").focus();
         showerror('member_cardid_image','请上传身份证正面照');
     }else{
         showsuccess('member_cardid_image');
     }
     if(member_cardid_back_image==''){
         $("#member_cardid_back_image").focus();
         showerror('member_cardid_back_image','请上传身份证反面照');
     }else{
         showsuccess('member_cardid_back_image');
     }
     if(member_cardid_person_image==''){
         $("#member_cardid_person_image").focus();
         showerror('member_cardid_person_image','请上传手持身份证照');
     }else{
         showsuccess('member_cardid_person_image');
     }
     var submitData={
         'form_submit' : 'ok',
         'formhash' : formhash,
         'member_truename':member_truename,
         'member_cardid_image':member_cardid_image,
         'member_cardid_back_image':member_cardid_back_image,
         'member_cardid_person_image':member_cardid_person_image
     };
     $.ajax({
         type : 'POST',
         url : 'index.php?act=member_real_name&op=real_name',
         data : submitData,
         contentType: 'application/x-www-form-urlencoded; charset=utf-8',
         dataType : 'json',
         success:function (data) {
             console.log(data);
            if(data.done=='true'){
                showalert('提交成功');
            }else{
                showalert(data.msg);
            }
         },
         timeout:15000,
         error:function (xhr, type) {
             showalert("网络不稳定，请稍后重试");
         }
     });
 });
</script>