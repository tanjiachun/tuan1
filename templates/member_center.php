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
                        <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=member_center" style="font-size: 12px;">· 个人资料</a></li>
                        <li><a href="index.php?act=member_real_name" style="font-size: 12px;">· 实名认证</a></li>
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
            <div class="member_message_set">
                <p><span>个人资料</span></p>
                <h3>亲爱的 <?php echo $this->member['member_phone'] ?> , 请填写真实详细的资料</h3>
                <div class="user-right">
                    <div class="user-info">
                        <div class="form-list">
                            <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                            <div class="form-item clearfix">
                                <label>手机号：</label>
                                <div class="form-item-value">
                                    <?php echo $this->member['member_phone'];?>
                                    <span style="color:#ff6905;cursor: pointer" class="member_phone_resume">[修改]</span>
                                </div>
                            </div>
                            <?php if(!empty($card)) { ?>
                                <div class="form-item clearfix">
                                    <label>等级：</label>
                                    <div class="form-item-value">
								<span class="VIP">
									<a class="imgVip"><image src="<?php echo $card['card_icon'];?>"></image></a>
                                	<a class="txtExplain"><?php echo $card['card_name'];?></a>
								</span>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-item clearfix">
                                <label>昵称：</label>
                                <div class="form-item-value">
                                    <input type="text" maxlength="8" id="member_nickname" name="member_nickname" value="<?php echo $this->member['member_nickname'];?>" class="form-input" placeholder="输入您的昵称">
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>头像：</label>
                                <div class="form-item-value">
                                    <div class="imgHeaderBox">
                                        <a href="javascript:;" class="headerImg" id="show_image_0">
                                            <?php if(empty($this->member['member_avatar'])) { ?>
                                                <img src="templates/images/peopleicon_01.gif">
                                            <?php } else { ?>
                                                <img src="<?php echo $this->member['member_avatar'];?>">
                                            <?php } ?>
                                        </a>
                                        <div class="updateInfo">
                                            <div class="opacityBox"></div>
                                            <a href="javascript:;" class="realBox">修改头像</a>
                                            <span class="img-file">
											<input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="other">
										</span>
                                        </div>
                                    </div>
                                    <input type="hidden" id="member_avatar" name="member_avatar" class="image_0" value="<?php echo $this->member['member_avatar'];?>" />
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>性别：</label>
                                <div class="form-item-value radio-box">
                                    <span class="radio<?php echo $this->member['member_sex'] == 1 ? ' active' : '';?>" field_value="1" field_key="member_sex"><i class="iconfont icon-type"></i>男</span>
                                    <span class="radio<?php echo $this->member['member_sex'] == 2 ? ' active' : '';?>" field_value="2" field_key="member_sex"><i class="iconfont icon-type"></i>女</span>
                                    <span class="radio<?php echo $this->member['member_sex'] == 0 ? ' active' : '';?>" field_value="0" field_key="member_sex"><i class="iconfont icon-type"></i>保密</span>
                                    <input type="hidden" id="member_sex" name="member_sex" value="<?php echo $this->member['member_sex'];?>" />
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>生日：</label>
                                <select id="member_birthyear" name="member_birthyear" onchange="showbirthday()">
                                    <option value="">请选择</option>
                                    <?php for($i=0; $i<100; $i++) { ?>
                                        <option value="<?php echo $current_year-$i;?>"<?php echo $this->member['member_birthyear'] == $current_year-$i ? ' selected="selected"' : '';?>><?php echo $current_year-$i;?></option>
                                    <?php } ?>
                                </select>
                                <em class="spacing">年</em>
                                <select id="member_birthmonth" name="member_birthmonth" onchange="showbirthday()">
                                    <option value="">请选择</option>
                                    <?php for($i=1; $i<=12; $i++) { ?>
                                        <option value="<?php echo $i;?>"<?php echo $this->member['member_birthmonth'] == $i ? ' selected="selected"' : '';?>><?php echo $i;?></option>
                                    <?php } ?>
                                </select>
                                <em class="spacing">月</em>
                                <select id="member_birthday" name="member_birthday">
                                    <option value="">请选择</option>
                                    <?php for($i=1; $i<=$days; $i++) { ?>
                                        <option value="<?php echo $i;?>"<?php echo $this->member['member_birthday'] == $i ? ' selected="selected"' : '';?>><?php echo $i;?></option>
                                    <?php } ?>
                                </select>
                                <em class="spacing">日</em>
                            </div>
                            <div class="form-item clearfix">
                                <label><span class="red">*</span>现居地址：</label>
                                <div class="form-item-value">
                                    <div class="second-province-box" prefix="nurse" style="display:inline-block">
                                        <div class="select-class">
                                            <a href="javascript:;" class="select-choice"><?php echo !empty($member_provincename) ? $member_provincename : '-省份-';?><i class="select-arrow"></i></a>
                                            <div class="select-list" style="display: none">
                                                <ul>
                                                    <li field_value="">-省份-</li>
                                                    <?php foreach($province_list as $key => $value) { ?>
                                                        <li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="second-city-box" style="display:inline-block">
                                        <?php if(!empty($member_city_list)) { ?>
                                            <div class="select-class">
                                                <a href="javascript:;" class="select-choice"><?php echo !empty($member_cityname) ? $member_cityname : '-城市-';?><i class="select-arrow"></i></a>
                                                <div class="select-list" style="display: none">
                                                    <ul>
                                                        <li field_value="">-城市-</li>
                                                        <?php foreach($member_city_list as $key => $value) { ?>
                                                            <li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="second-area-box" style="display:inline-block">
                                        <?php if(!empty($member_area_list)) { ?>
                                            <div class="select-class">
                                                <a href="javascript:;" class="select-choice"><?php echo !empty($member_areaname) ? $member_areaname : '-州县-';?><i class="select-arrow"></i></a>
                                                <div class="select-list" style="display: none">
                                                    <ul>
                                                        <li field_value="">-州县-</li>
                                                        <?php foreach($member_area_list as $key => $value) { ?>
                                                            <li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <input type="hidden" id="nurse_provinceid" name="nurse_provinceid" value="<?php echo $member_provinceid;?>" />
                                    <input type="hidden" id="nurse_cityid" name="nurse_cityid" value="<?php echo $member_cityid;?>" />
                                    <input type="hidden" id="nurse_areaid" name="nurse_areaid" value="<?php echo $member_areaid;?>" />
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label>&nbsp;</label>
                                <div class="form-item-value">
                                    <a href="javascript:checksubmit();" class="btn btn-primary">提交保存</a><span class="return-success"></span>
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
    function showbirthday(){
        $('#member_birthday').html('');
        $('#member_birthday').append('<option value="">请选择</option>');
        for(var i=1; i<=28; i++){
            $('#member_birthday').append('<option value="'+i+'">'+i+'</option>');
        }
        if($('#member_birthmonth').val() != '2'){
            $('#member_birthday').append('<option value="29">29</option>');
            $('#member_birthday').append('<option value="30">30</option>');
            switch($('#member_birthmonth').val()){
                case '1':
                case '3':
                case '5':
                case '7':
                case '8':
                case '10':
                case '12':{
                    $('#member_birthday').append('<option value="31">31</option>');
                }
            }
        } else if($('#member_birthyear').val() != '') {
            var nbirthyear = $('#member_birthyear').val();
            if(nbirthyear%400 == 0 || (nbirthyear%4 == 0 && nbirthyear%100 != 0)) {
                $('#member_birthday').append('<option value="29">29</option>');
            }
        }
    }
    var submit_btn = false;
    function checksubmit() {
        var formhash = $('#formhash').val();
        var member_nickname = $('#member_nickname').val();
        var member_avatar = $('#member_avatar').val();
        var member_sex = $('#member_sex').val();
        var member_birthyear = $('#member_birthyear').val();
        var member_birthmonth = $('#member_birthmonth').val();
        var member_birthday = $('#member_birthday').val();
        var member_provinceid=$("#nurse_provinceid").val();
        var member_cityid=$("#nurse_cityid").val();
        var member_areaid=$("#nurse_areaid").val();
        var submitData = {
            'form_submit' : 'ok',
            'formhash' : formhash,
            'member_nickname' : member_nickname,
            'member_avatar' : member_avatar,
            'member_sex' : member_sex,
            'member_birthyear' : member_birthyear,
            'member_birthmonth' : member_birthmonth,
            'member_birthday' : member_birthday,
            'member_provinceid':member_provinceid,
            'member_cityid':member_cityid,
            'member_areaid':member_areaid

        };
        if(submit_btn) return;
        submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_center&op=member_resume',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data) {
                submit_btn = false;
                if(data.done == 'true') {
                    $('.return-success').html('修改成功');
                    $('.return-success').show();
                    setTimeout(function(){
                        window.location.href = 'index.php?act=member_center';
                    }, 1000);
                } else {
                    showalert('提交错误');
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                submit_btn = false;
                showalert('网路不稳定，请稍候重试');
            }
        });
    }
</script>