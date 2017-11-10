<?php include(template('common_header'));?>
<link rel="stylesheet" href="templates/css/admin.css">
<style>
    #member_address_choose{
        color: #000;
        min-height: 200px;
        margin-bottom: 20px;
    }
    #member_address_choose ul li {
        margin:10px 0 10px 0;
        height:30px;
        line-height: 30px;
        border: 1px solid transparent;
    }
    #member_address_choose ul li.active{
        background: #fff0e8;
        /*border: 1px solid #ff6905;*/
        font-weight: 600;
    }
    #member_address_choose ul li.on{
        background: #fff0e8;
        border: 1px solid #ff6905;
        font-weight: 600;
    }
    .resume_address_btn{
        margin-right:10px;display:none;color: #3366cc;cursor: pointer;
        font-size: 12px;
        font-weight: 400;
    }
    .resume_address_btn.active{
        display: block;
    }
    .resume_address_btn.on{
        display: block;
    }
    .add_address_btn{
        display: inline-block;
        margin-left: 60px;
        background: linear-gradient(#eee,#ccc);
        border: 1px solid #ddd;
        padding:0 5px;
        border-radius: 2px;
        cursor: pointer;
    }

</style>
<div class="nurse_header">
    <a class="left" href="index.php">
        <img src="templates/images/logo.png">
    </a>
    <div class=" right" style="height:74px;line-height: 74px;">
        <img src="templates/images/lc1.png" alt="">
    </div>
</div>
</div>
<div style="margin:20px auto 0;width:1180px;">
    <div id="member_address_choose">
        <div style="overflow: hidden;border-bottom: 1px solid #ddd;margin-bottom: 10px;">
            <span class="left">确认工作地址</span>
            <span class="right"><a target="_blank" style="color: #0000CC;font-size: " href="index.php?act=member_address_set">管理工作地址</a></span>
        </div>
        <ul>
            <?php foreach ($address_list as $key => $value) { ?>
                <li style="position: relative;"<?php echo $value['choose_state']==1 ? ' class="on"' : '';?>>
                    <span style="display: inline-block;width:60px;"></span>
                    <input type="radio" class="address_choose" name="address_choose" value="<?php echo $value['member_address_id'] ?>" <?php echo $value['choose_state']==1 ? ' checked="checked"' : '';?>>
                    <span class="address_content"><?php echo $value['member_areainfo'] ?> <?php echo $value['address_content'] ?></span> （联系人：<span class="address_member_name"><?php echo $value['address_member_name'] ?></span>） <span class="address_member_phone"><?php echo $value['address_phone'] ?></span>
                    <span class="resume_address_btn right <?php echo $value['choose_state']==1 ? 'active' : '';?>" data="<?php echo $value['member_address_id'] ?>">修改地址</span>
                </li>
            <?php } ?>
        </ul>
        <span class="add_address_btn"><img style="margin:0 5px 2px 0;" src="templates/images/sum.png" alt="">添加工作地址</span>
    </div>
    <div id="order_nurse_message">
        <div style="overflow: hidden;margin-bottom: 10px;color: #000;">
            <span class="left">确认订单信息</span>
        </div>
        <style>
            .order-tb thead th{
                background: #fff;
                border-bottom: 2px solid #b2d1ff;
            }
            .invoice-box .invoice-form .invoice-form-item span{
                width: 100px;
            }
        </style>
        <div class="orderlist">
            <div class="orderlist-body">
                <table class="order-tb">
                    <input type="hidden" id="book_ids" value="<?php echo $pay_ids ?>">
                    <input type="hidden" id="deposit_amount" value="<?php echo $total_price ?>">
                    <input type="hidden" id="agent_id" value="<?php echo $agent_id ?>">
                    <thead>
                    <tr>
                        <th width="200">家政人员简历</th>
                        <th width="150">交易详情</th>
                        <th width="150">价格</th>
                        <th width="150">优惠方式</th>
                        <th width="100">小计</th>
                    </tr>
                    </thead>
                    <?php foreach ($book_list as $key => $value) { ?>
                        <tbody>
                        <tr class="sep-row"><td colspan="5"></td></tr>
                        <tr class="tr-th">
                            <td colspan="5">
                                <span style="color:#000;">机构：<?php echo $agent_list[$value['agent_id']]['agent_name'];?></span>
                                <span style="color: #000;">联系电话：<?php echo $value['book_phone'];?></span>
<!--                                <span> <a href="javascript:;"><img style="margin: 0 5px 2px 0" src="templates/images/lianxi.png" alt="">和我联系</a></span>-->
                            </td>
                        </tr>
                        <tr class="tr-bd">
                            <td>
                                <div class="td-inner clearfix w-300">
                                    <div class="item-pic">
                                        <a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><img src="<?php echo $nurse_list[$value['nurse_id']]['nurse_image'];?>"></a>
                                    </div>
                                    <div class="item-info">
                                        <a style="overflow: inherit;margin-right: 20px;" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><?php echo $nurse_list[$value['nurse_id']]['nurse_nickname'];?></a>
                                        <span><?php echo $nurse_list[$value['nurse_id']]['nurse_special_service'];?></span>
                                    </div>
                                    <div class="item_image">
                                        <?php if($nurse_list[$value['nurse_id']]['promise_state']==2) { ?>
                                            <img src="templates/images/three_days.jpg" alt="">三小时无理由
                                        <?php } elseif($nurse_list[$value['nurse_id']]['promise_state']==4) { ?>
                                            <img src="templates/images/three_days.jpg" alt="">三天无理由
                                        <?php } ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php echo $value['book_details'] ?>
                            </td>
                            <td>
                                <?php if($nurse_list[$value['nurse_id']]['nurse_type']==3) { ?>
                                    每小时¥<?php echo $value['nurse_price'] ?>
                                <?php } else if($nurse_list[$value['nurse_id']]['nurse_type']==4) { ?>
                                    每平方¥<?php echo $value['nurse_price'] ?>
                                <?php } else if($nurse_list[$value['nurse_id']]['nurse_type']==5 || $nurse_list[$value['nurse_id']]['nurse_type']==6) { ?>
                                    每月¥<?php echo $value['nurse_price'] ?><br>
                                    （以26天计）
                                <?php } else if($nurse_list[$value['nurse_id']]['nurse_type']==7 || $nurse_list[$value['nurse_id']]['nurse_type']==8) { ?>
                                    每次¥<?php echo $value['nurse_price'] ?>
                                <?php } else if($nurse_list[$value['nurse_id']]['nurse_type']==9 || $nurse_list[$value['nurse_id']]['nurse_type']==10) { ?>
                                    每人¥<?php echo $value['nurse_price'] ?><br>
                                    <?php if(!empty($value['work_cars'])) { ?>
                                        (<?php echo $value['work_cars'] ?>吨车，¥ <?php echo $value['car_price'] ?>)
                                    <?php } ?>
                                <?php } else if($nurse_list[$value['nurse_id']]['nurse_type']==11 || $nurse_list[$value['nurse_id']]['nurse_type']==12) { ?>
                                    每个学生每月¥<?php echo $value['nurse_price'] ?><br>
                                    <?php if(!$nurse['students_sale']) { ?>
                                        (多余一个学生半价)
                                    <?php } else { ?>
                                        (多余一个学生无优惠)
                                    <?php } ?>
                                <?php } else { ?>
                                    服务费 ¥ <?php echo $value['service_price'] ?> <br>
                                    本月工资 ¥ <?php echo $value['nurse_price'] ?>
                                <?php } ?>
                            </td>
                            <td>
                                团家政 <span style="color: #ff6905;"><?php echo $nurse_list[$value['nurse_id']]['nurse_discount']*10 ?></span> 折优惠
                            </td>
                            <td>
                                <span style="color: #ff6905;font-size: 16px;font-weight: 700;"><?php echo $value['deposit_amount'] ?></span>
                            </td>
                        </tr>
                        </tbody>
                    <?php } ?>
                    <tbody>
                    <tr class="sep-row"><td colspan="5"></td></tr>
                    <tr class="tr-ft">
                        <td colspan="5" style="background: #f2f7ff;">
                            <div class="reture-exp">
                                <label>给对方留言：<?php echo $value['book_message'];?></label>
                                <input type="text" class="form-input w-800 member_leave_message">
                            </div>
                            <div style="text-align: right;">
                                合计：<span style="color: #ff6905;font-size: 16px;font-weight: 700;">¥ <?php echo $total_price ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr class="tf-ft">
                        <td colspan="5" style="border:none;border-bottom: 1px solid #ddd;">
                            <div class="invoice-box" style="margin-top: 10px;">
                                <div class="select-box invoice-radio-box" style="position: relative;">
                                    <span style="display: inline-block;width:10px;"><input type="checkbox" id="invoice" style="position: absolute;bottom:2px;"></span>
                                    <span class="radio" order_invoice="yes" style="font-size: 6px;font-weight: 100;color: gray;">开具发票</span>
                                    <em class="t-tips" style="display: none;">发票内容由其机构决定，发票由机构开具并寄出</em>
                                </div>
                                <div class="invoice_type_choose" style="margin-top: 10px;display: none;">
                                    <input type="hidden" id="invoice_type" value="">
                                    <span class="radio active" order_invoice="个人"><i class="iconfont icon-type"></i>个人</span>
                                    <span class="radio" order_invoice="单位"><i class="iconfont icon-type"></i>单位</span>
                                </div>
                                <script>
                                    $(".radio").click(function () {
                                        $(this).addClass("active").siblings().removeClass("active");
                                        if($(this).attr('order_invoice')=="个人"){
                                            $(".person").show();
                                            $(".unit").hide();
                                            $(".address").hide();
                                            $("#invoice_type").val("个人");
                                        }else if($(this).attr('order_invoice')=="单位"){
                                            $(".person").hide();
                                            $(".unit").show();
                                            $(".address").hide();
                                            $("#invoice_type").val("单位");
                                        }
                                    });
                                </script>
                                <div class="invoice-form person" style="display: none;">
                                    <div class="invoice-form-item">
                                        <span>发票抬头</span>
                                        <input id="invoice_title" name="invoice_title" type="text">
                                        <div class="Validform-checktip Validform-wrong"></div>
                                        <em class="t-tips">遵循税务局相关规定，发票抬头必须为个人姓名或公司名称</em>
                                    </div>
                                    <div class="invoice-form-item">
                                        <span>发票明细</span>
                                        <input id="invoice_content" name="invoice_content" type="text">
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                    <div class="invoice-form-item">
                                        <span>收件人</span>
                                        <input id="invoice_membername" name="invoice_membername" type="text">
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                    <div class="invoice-form-item">
                                        <span></span>
                                        <span><a href="javascript:;" class="next_step">下一步</a></span>
                                    </div>
                                </div>
                                <div class="invoice-form unit" style="display: none;">
                                    <div class="invoice-form-item">
                                        <span>单位名称</span>
                                        <input id="unit_name" name="unit_name" type="text">
                                        <div class="Validform-checktip Validform-wrong"></div>
                                        <em class="t-tips">遵循税务局相关规定，发票抬头必须为个人姓名或公司名称</em>
                                    </div>
                                    <div class="invoice-form-item">
                                        <span>纳税人识别码</span>
                                        <input id="invoice_code" name="invoice_code" type="text">
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                    <div class="invoice-form-item">
                                        <span>收件人</span>
                                        <input id="invoice_unit_membername" name="invoice_unit_membername" type="text">
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                    <div class="invoice-form-item">
                                        <span></span>
                                        <span><a href="javascript:;" class="next_step">下一步</a></span>
                                    </div>
                                </div>
                                <div class="invoice-form address" style="display: none;">
                                    <div class="invoice-form-item">
                                        <span>邮寄地址</span>
                                        <div class="first-province-box" prefix="invoice" style="display:inline-block">
                                            <div class="select-class">
                                                <a href="javascript:;" class="select-choice">-省份-<i class="select-arrow"></i></a>
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
                                        <div class="first-city-box" style="display:inline-block"></div>
                                        <div class="first-area-box" style="display:inline-block"></div>
                                        <input type="hidden" id="invoice_provinceid" name="invoice_provinceid" value="" />
                                        <input type="hidden" id="invoice_cityid" name="invoice_cityid" value="" />
                                        <input type="hidden" id="invoice_areaid" name="invoice_areaid" value="" />
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                    <div class="invoice-form-item">
                                        <span>&nbsp;</span>
                                        <input id="invoice_address" name="invoice_address" type="text">
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <script>
                        $(".next_step").click(function () {
                            if($("#invoice_type").val()=='个人'){
                                var invoice_title=$("#invoice_title").val();
                                var invoice_content=$("#invoice_content").val();
                                var invoice_membername=$("#invoice_membername").val();
                                if(invoice_title==''){
                                    showerror('invoice_title','请填写发票抬头');
                                    return;
                                }else{
                                    showsuccess('invoice_title');
                                }
                                if(invoice_content==''){
                                    showerror('invoice_content','请填写发票明细');
                                    return;
                                }else{
                                    showsuccess('invoice_content');
                                }
                                if(invoice_membername==''){
                                    showerror('invoice_membername','请填写收件人');
                                    return;
                                }else{
                                    showsuccess('invoice_membername');
                                }
                                $(".person").hide();
                                $(".address").show();
                            }else if($("#invoice_type").val()=='单位'){
                                var unit_name=$("#unit_name").val();
                                var invoice_code=$("#invoice_code").val();
                                var invoice_unit_membernam=$("#invoice_unit_membernam").val();
                                if(unit_name==''){
                                    showerror('unit_name','请填写单位名称');
                                    return;
                                }else{
                                    showsuccess('unit_name');
                                }
                                if(invoice_code==''){
                                    showerror('invoice_code','请填写纳税人识别码');
                                    return;
                                }else{
                                    showsuccess('invoice_code');
                                }
                                if(invoice_unit_membername==''){
                                    showerror('invoice_unit_membername','请填写收件人');
                                    return;
                                }else{
                                    showsuccess('invoice_unit_membername');
                                }
                                $(".unit").hide();
                                $(".address").show();
                            }

                        });
                        $(".unit .first-province-box li").click(function () {
                            var data=$(this).attr('field_value');
                            $("#invoice_unit_provinceid").val(data);
                        });
                        $(".unit .first-city-box li").click(function () {
                            var data=$(this).attr('field_value');
                            $("#invoice_unit_cityid").val(data);
                        });
                        $(".unit .first-area-box li").click(function () {
                            var data=$(this).attr('field_value');
                            $("#invoice_unit_areaid").val(data);
                        });
                        $("#invoice").click(function () {
                            if($(this).is(":checked")){
                                $(".t-tips").show();
                                $(".invoice_type_choose").show();
                                $(".person").fadeIn();
                                $("#invoice_type").val("个人");
                            }else{
                                $(".t-tips").hide();
                                $(".invoice_type_choose").hide();
                                $(".address").hide();
                                $(".person").fadeOut();
                                $(".unit").fadeOut();
                                $("#invoice_type").val("");
                            }
                        });
                    </script>
                    <tr class="tr-ft">
                        <td colspan="5" style="border: none;border-bottom: 1px dotted #e5e5e5;">
                            <div class="right">
                                <input style="margin:3px 5px 0 0;" type="checkbox" id="hide_pay" checked>匿名付款
                            </div>
                        </td>
                    </tr>
                    <tr class="tr-ft">
                        <td colspan="5" style="border:none;padding-right: 0;padding-bottom: 0;">
                            <div class="right" style="border:1px solid #ff6905;padding:20px 60px 20px 60px;">
                                <span style="color: #000;">实&nbsp;&nbsp;付&nbsp;&nbsp;款：</span> <span style="font-size: 18px;font-weight: 700;">¥</span> <b style="font-size: 24px;font-weight: 700;color: #ff6905;" id="true_price"><?php echo $total_price ?></b>
                                <br>
                                <span style="color: #000;">工作地址：</span><span class="work_address"><?php echo $member_address['member_areainfo'] ?> <?php echo $member_address['address_content'] ?></span>
                                <br>
                                <span style="color: #000;">联&nbsp;&nbsp;系&nbsp;&nbsp;人：</span><span class="member_name" style="color: #000;"><?php echo $member_address['address_member_name'] ?></span> <span class="member_phone"><?php echo $member_address['address_phone'] ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr class="tr-ft">
                        <td colspan="5" style="padding: 0;border: none;">
                            <div class="right">
                                <span style="background: #ff6905;color: #fff;display: inline-block;height:40px;line-height: 40px;width:160px;text-align: center;cursor: pointer;font-size: 16px;" class="order_submit_btn">提交订单</span>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal-wrap w-700" id="resume-box" style="height:450px;display: none;">
    <div class="modal-hd">
        <div class="Validform-checktip Validform-wrong m-tip"></div>
        <input type="hidden" id="resume_id" name="resume_id" value="" />
        <h4><uik>地址修改</uik></h4>
        <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
    </div>
    <div class="modal-bd">
        <div class="user-right">

        </div>
    </div>
    <div class="modal-ft">
        <a class="btn btn-default onwork-quxiao" onclick="Custombox.close();">取消</a>
    </div>
</div>

<div class="modal-wrap w-700" id="add-box" style="height:450px;display: none;">
    <div class="modal-hd">
        <div class="Validform-checktip Validform-wrong m-tip"></div>
        <h4><uik>添加新地址</uik></h4>
        <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
    </div>
    <div class="modal-bd">
        <div class="user-right">
            <div class="user-right">
                <div class="user-info" style="min-height:300px;">
                    <div class="form-list">
                        <div class="form-item clearfix">
                            <label><span class="red">*</span>现居地址：</label>
                            <div class="form-item-value">
                                <div class="second-province-box" prefix="member" style="display:inline-block">
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
                                <input type="hidden" id="nurse_provinceid" name="nurse_provinceid" value="" />
                                <input type="hidden" id="nurse_cityid" name="nurse_cityid" value="" />
                                <input type="hidden" id="nurse_areaid" name="nurse_areaid" value="" />
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
                            <label><span class="red">*</span>详细地址：</label>
                            <div class="form-item-value">
                                <input type="text" id="add_address_content" name="add_address_content" value="" class="form-input w-300" placeholder="输入你的详细地址">
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
                            <label><span class="red">*</span>联系人姓名：</label>
                            <div class="form-item-value">
                                <input type="text" id="add_address_member_name" name="add_address_member_name" value="" class="form-input w-200" placeholder="请输入联系人姓名">
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
                            <label><span class="red">*</span>手机号码：</label>
                            <div class="form-item-value">
                                <input type="text" id="add_address_phone" name="add_address_phone" value="" class="form-input w-200" placeholder="请输入电话号码">
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
                            <label></label>
                            <div class="form-item-value">
                                <input type="checkbox" id="add_member_selected" name="add_member_selected" value="">设为默认地址
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-ft">
        <a class="btn btn-default onwork-quxiao" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="addsubmit();">添加</a>
    </div>
</div>
<div class="modal-wrap w-600" id="book-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-message">请等待阿姨联系您，进一步沟通服务细节</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default bookwait" book_sn="">等待沟通</a>
        <a class="btn btn-primary bookpayment" book_sn="">支付定金</a>
    </div>
</div>
<div class="modal-wrap w-600" id="wait-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
                    	<i class="iconfont icon-info"></i>
                    </span>
                <h3 class="tip-title">当沟通完成，您可以在【个人中心】-【我的订单】中，完成定金支付。</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" href="index.php?act=member_book">知道了</a>
    </div>
</div>
<div class="modal-wrap w-400" id="login-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-title">您还未登录了</h3>
                <div class="tip-hint">3 秒后页面跳转</div>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" href="index.php?act=login">确定</a>
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
    $("#member_address_choose ul li").mouseover(function () {
        $(this).children(".resume_address_btn").addClass("on");
        $(this).addClass("active");
    });
    $("#member_address_choose ul li").mouseout(function () {
        $(this).children(".resume_address_btn").removeClass("on");
        $(this).removeClass("active");
    });
    $("#member_address_choose ul li").click(function () {
        $(this).addClass("on").siblings().removeClass("on");
        $(this).children("input").prop('checked','checked');
        $(this).children(".resume_address_btn").addClass("active");
        $(this).siblings().children(".resume_address_btn").removeClass("active");
        $(".work_address").html($(this).children(".address_content").html());
        $(".member_name").html($(this).children(".address_member_name").html());
        $(".member_phone").html($(this).children(".address_member_phone").html());
    });
    $(".resume_address_btn").click(function () {
        var member_address_id = $(this).attr('data');
        Custombox.open({
            target: 'index.php?act=place_order&op=order_address_resume&member_address_id='+member_address_id,
            effect: 'blur',
            overlayClose: true,
            speed:500,
            overlaySpeed: 300,
        });
    });
    var edit_submit_btn = false;
    function editsubmit() {
        var formhash = $('#formhash').val();
        var address_id = $('#address_id').val();
        var true_name = $('#true_name').val();
        var mobile_phone = $('#mobile_phone').val();
        var province_id = $('#province_id').val();
        var city_id = $('#city_id').val();
        var area_id = $('#area_id').val();
        var address_info = $('#address_info').val();
        if(true_name == '') {
            showwarning('address-box', '请输入联系人');
            return;
        }
        if(mobile_phone == '') {
            showwarning('address-box', '请输入电话');
            return;
        }
        var regu = /^[1][0-9]{10}$/;
        if(!regu.test(mobile_phone)) {
            showwarning('address-box', '电话格式不正确');
            return;
        }
        if(province_id == '' || city_id == '') {
            showwarning('address-box', '请选择所在地区');
            return;
        }
        if(address_info == '') {
            showwarning('address-box', '请输入详细地址');
            return;
        }
        var submitData = {
            'form_submit' : 'ok',
            'formhash' : formhash,
            'member_address_id' : address_id,
            'address_member_name' : true_name,
            'address_phone' : mobile_phone,
            'member_provinceid' : province_id,
            'member_cityid' : city_id,
            'member_areaid' : area_id,
            'address_content' : address_info
        };
        console.log(submitData);
        if(edit_submit_btn) return;
        edit_submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=place_order&op=order_address_resume',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                edit_submit_btn = false;
                if(data.done == 'true') {
                    Custombox.close(function() {
                        setTimeout(function () {
                            window.location.reload();
                        },1000)
                    });
                } else {
                    showwarning('address-box', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                edit_submit_btn = false;
                showwarning('address-box', '网路不稳定，请稍候重试');
            }
        });
    }

    $(".add_address_btn").click(function () {
        Custombox.open({
            target : '#add-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300
        });
    });
    function addsubmit() {
        var member_provinceid=$("#nurse_provinceid").val();
        var member_cityid=$("#nurse_cityid").val();
        var member_areaid=$("#nurse_areaid").val();
        var address_content=$("#add_address_content").val();
        var address_member_name=$("#add_address_member_name").val();
        var address_phone=$("#add_address_phone").val();
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
        if($("#add_member_selected").is(":checked")){
            member_selected=1;
        }else{
            member_selected=0;
        }
        var submitData={
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
            url : 'index.php?act=place_order&op=address_add',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data) {
                console.log(data);
                if(data.done == 'true') {
                    setTimeout(function(){
                        window.location.reload();
                    }, 1000);
                } else {
                    showwarning('add-box', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showwarning('add-box', '网络不稳定，请稍后重试');
            }
        });
    }
</script>
<script>
    $(".order_submit_btn").click(function () {
        var book_sub_order=$("#book_ids").val();
        var book_address=$(".work_address").html();
        var service_member_phone=$(".member_phone").html();
        var service_member_name=$(".member_name").html();
        var deposit_amount=$("#deposit_amount").val();
        var agent_id=$("#agent_id").val();
        var book_message=$(".member_leave_message").val();

        var invoice_state;
        if($("#invoice").is(":checked")){
            invoice_state=1;
        }else{
            invoice_state=0;
        }
        var invoice_type=$("#invoice_type").val();

        var invoice_title=$("#invoice_title").val();
        var invoice_content=$("#invoice_content").val();
        var invoice_membername=$("#invoice_membername").val();
        var invoice_provinceid=$("#invoice_provinceid").val();
        var invoice_cityid=$("#invoice_cityid").val();
        var invoice_areaid=$("#invoice_areaid").val();
        var invoice_address=$("#invoice_address").val();
        var unit_name=$("#unit_name").val();
        var invoice_code=$("#invoice_code").val();
        var invoice_unit_membername=$("#invoice_unit_membername").val();
        if(invoice_state==1){
            if(invoice_type=='个人'){
                if(invoice_title==''){
                    showerror('invoice_title','请填写发票抬头');
                    return;
                }else{
                    showsuccess('invoice_title');
                }
                if(invoice_content==''){
                    showerror('invoice_content','请填写发票明细');
                    return;
                }else{
                    showsuccess('invoice_content');
                }
                if(invoice_membername==''){
                    showerror('invoice_membername','请填写收件人');
                    return;
                }else{
                    showsuccess('invoice_membername');
                }
                if(invoice_provinceid==''){
                    showerror('invoice_provinceid','请选择省份');
                    return;
                }else{
                    showsuccess('invoice_provinceid');
                }
                if(invoice_cityid==''){
                    showerror('invoice_cityid','请选择城市');
                    return;
                }else{
                    showsuccess('invoice_cityid');
                }
                if(invoice_address==''){
                    showerror('invoice_address','请填写详细地址');
                    return;
                }else{
                    showsuccess('invoice_address');
                }
            }else if(invoice_type=='单位'){
                if(unit_name==''){
                    showerror('unit_name','请填写单位名称');
                    return;
                }else{
                    showsuccess('unit_name');
                }
                if(invoice_code==''){
                    showerror('invoice_code','请填写纳税人识别码');
                    return;
                }else{
                    showsuccess('invoice_code');
                }
                if(invoice_unit_membername==''){
                    showerror('invoice_unit_membername','请填写收件人');
                    return;
                }else{
                    showsuccess('invoice_unit_membername');
                }
                if(invoice_provinceid==''){
                    showerror('invoice_provinceid','请选择省份');
                    return;
                }else{
                    showsuccess('invoice_provinceid');
                }
                if(invoice_cityid==''){
                    showerror('invoice_cityid','请选择城市');
                    return;
                }else{
                    showsuccess('invoice_cityid');
                }
                if(invoice_address==''){
                    showerror('invoice_address','请填写详细地址');
                    return;
                }else{
                    showsuccess('invoice_address');
                }
            }
        }

        var submitData={
            'book_sub_order':book_sub_order,
            'agent_id':agent_id,
            'book_address':book_address,
            'service_member_phone':service_member_phone,
            'service_member_name':service_member_name,
            'deposit_amount':deposit_amount,
            'book_message':book_message,

            'invoice_type':invoice_type,
            'invoice_title':invoice_title,
            'invoice_content':invoice_content,
            'invoice_membername':invoice_membername,
            'invoice_provinceid':invoice_provinceid,
            'invoice_cityid':invoice_cityid,
            'invoice_areaid':invoice_areaid,
            'invoice_address':invoice_address,
            'unit_name':unit_name,
            'invoice_code':invoice_code,
            'invoice_unit_membername':invoice_unit_membername
        };
        console.log(submitData);
        if(book_address==''){
            showalert('请选择工作地址');
        }
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_book&op=order',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data) {
                if(data.done == 'true') {
                    $('.bookpayment').attr('book_sn', data.book_sn);
                    $('.bookwait').attr('book_sn', data.book_sn);
                    Custombox.open({
                        target : '#book-box',
                        effect : 'blur',
                        overlayClose : true,
                        speed : 500,
                        overlaySpeed : 300,
                    });
                } else if(data.done == 'login') {
                    Custombox.open({
                        target : '#login-box',
                        effect : 'blur',
                        overlayClose : true,
                        speed : 500,
                        overlaySpeed : 300,
                        open: function () {
                            setTimeout(function(){
                                window.location.href = 'index.php?act=login';
                            }, 3000);
                        },
                    });
                } else {
                    if(data.id == 'system') {
                        showalert(data.msg);
                    }else{
                        showalert('网路不稳定，请稍候重试');
                    }
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showalert('网路不稳定，请稍候重试');
            }
        });
    });
    $('.bookwait').on('click', function() {
        Custombox.close();
        var book_sn = $(this).attr('book_sn');
        $.getJSON('index.php?act=book&op=message&book_sn='+book_sn, function(data){
            Custombox.open({
                target : '#wait-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
        });
    })

    $('.bookpayment').on('click', function() {
        var book_sn = $(this).attr('book_sn');
        window.location.href = 'index.php?act=book&op=payment&book_sn='+book_sn;
    });
</script>