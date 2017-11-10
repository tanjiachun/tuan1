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
                        <li><a href="index.php?act=member_real_name" style="font-size: 12px;">· 实名认证</a></li>
                        <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=member_address_set" style="font-size: 12px;">· 地址管理</a></li>
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
            <div class="member_address_set">
                <p><span>地址管理</span></p>
                <div class="user-right">
                    <div class="user-info">
                        <div class="form-list">
                            <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
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
                                <label><span class="red">*</span>详细地址：</label>
                                <div class="form-item-value">
                                    <input type="text" id="address_content" name="address_content" value="" class="form-input w-300" placeholder="输入你的详细地址">
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label><span class="red">*</span>联系人姓名：</label>
                                <div class="form-item-value">
                                    <input type="text" id="address_member_name" name="address_member_name" value="" class="form-input w-200" placeholder="请输入联系人姓名">
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label><span class="red">*</span>手机号码：</label>
                                <div class="form-item-value">
                                    <input type="text" id="address_phone" name="address_phone" value="" class="form-input w-200" placeholder="请输入电话号码">
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label></label>
                                <div class="form-item-value">
                                    <input type="checkbox" id="member_selected" name="member_selected" value="">设为默认地址
                                    <div class="Validform-checktip Validform-wrong"></div>
                                </div>
                            </div>
                            <div class="form-item clearfix">
                                <label></label>
                                <div class="form-item-value">
                                    <span class="member_submit_btn" id="member_submit_btn">保存</span>
                                    <span class="return_success" style="color: #ff6905;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="member_address_count">
                <p>已保存了 <span><?php echo $count ?></span> 条地址，还可以保存 <span><?php echo $also_count ?></span> 条地址</p>
            </div>
            <div class="member_address_show">
                <div class="orderlist">
                    <div class="orderlist-body">
                        <table class="order-tb">
                            <thead>
                            <tr>
                                <th width="100">联系人</th>
                                <th width="220">所在地区</th>
                                <th width="220">详细地址</th>
                                <th width="140">电话/手机</th>
                                <th width="100">操作</th>
                                <th width="100">状态</th>
                            </tr>
                            </thead>
                            <?php foreach($address_list as $key => $value) { ?>
                                <tbody id="address_<?php echo $value['member_address_id'];?>">
                                <tr class="tr-bd">
                                    <td><?php echo $value['address_member_name'] ?></td>
                                    <td><?php echo $value['member_areainfo'] ?></td>
                                    <td><?php echo $value['address_content'] ?></td>
                                    <td><?php echo $value['address_phone'] ?></td>
                                    <td><a href="index.php?act=member_address_set&op=address_resume&member_address_id=<?php echo $value['member_address_id'] ?>">修改</a>/
                                        <a href="javascript:;" class="del_address_btn" data="<?php echo $value['member_address_id'] ?>">删除</a></td>
                                    <?php if($value['member_address_id']==$this->member['show_address_id']) { ?>
                                        <td>默认选中</td>
                                    <?php } else { ?>
                                        <td></td>
                                    <?php } ?>
                                </tr>
                                </tbody>
                            <?php } ?>
                        </table>
                    </div>
                    <?php echo $multi;?>
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

<div class="alert-box" style="display:none;">
    <div class="alert alert-danger tip-title"></div>
</div>

<div class="modal-wrap w-400" id="del-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="del_id" name="del_id" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定删除吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="delsubmit();">确定</a>
    </div>
</div>
<script>
    $(".member_submit_btn").click(function () {
        var formhash = $('#formhash').val();
        var member_provinceid=$("#nurse_provinceid").val();
        var member_cityid=$("#nurse_cityid").val();
        var member_areaid=$("#nurse_areaid").val();
        var address_content=$("#address_content").val();
        var address_member_name=$("#address_member_name").val();
        var address_phone=$("#address_phone").val();
        if(member_provinceid==''){
            showerror('nurse_provinceid','请选择省份');
            return;
        }else{
            showsuccess('nurse_provinceid');
        }
        if(member_cityid==''){
            showerror('nurse_cityid','请选择市');
            return;
        }else{
            showsuccess('nurse_cityid');
        }
        if(address_content==''){
            showerror('address_content','请填写详细地址');
            return;
        }else{
            showsuccess('address_content');
        }
        if(address_member_name==''){
            showerror('address_member_name','请填写联系人姓名');
            return;
        }else{
            showsuccess('address_member_name');
        }
        if(address_phone==''){
            showerror('address_phone','请填写联系号码');
            return;
        }else{
            showsuccess('address_phone');
        }
        var reg2 = /^[1][0-9]{10}$/;
        if(!reg2.test(address_phone)){
            showerror('address_phone', '手机号格式不正确');
            return;
        }else{
            showsuccess('address_phone');
        }
        var member_selected;
        if($("#member_selected").is(":checked")){
            member_selected=1;
        }else{
            member_selected=0;
        }
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'member_provinceid':member_provinceid,
            'member_cityid':member_cityid,
            'member_areaid':member_areaid,
            'address_content':address_content,
            'address_member_name':address_member_name,
            'address_phone':address_phone,
            'member_selected':member_selected
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_address_set&op=address_set',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data) {
                console.log(data);
                if(data.done == 'true') {
                    $(".return_success").html('添加成功');
                    setTimeout(function(){
                        window.location.href = 'index.php?act=member_address_set';
                    }, 2000);
                } else {
                    showalert(data.msg);
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
    $(".del_address_btn").click(function () {
        var item=$(this).attr('data');
        $("#del_id").val(item);
        Custombox.open({
            target : '#del-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    function delsubmit() {
        var member_address_id=$("#del_id").val();
        var submitData={
            'member_address_id':member_address_id
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_address_set&op=del_address',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('#address_'+data.member_address_id).remove();
                        $('.alert-box .tip-title').html('删除成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 2000);
                    });
                }else{
                    showwarning('del-box', data.msg);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                showwarning('del-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>





















