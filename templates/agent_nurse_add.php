<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
        .form-item-value label{
            display: inline-block;
            min-width:105px;
        }
        .form-item{
            margin-bottom: 10px;
        }
    </style>
    <div class="agent_set_center_header">
        <div class="left" style="margin-top: 10px;">
            <a href="index.php">
                <img src="templates/images/logo.png">
            </a>
            <span style="font-size: 18px;font-weight: 500;margin-left: 5px;">机构管理平台</span>
        </div>
        <div class="left agent_message_show">
            <span>被关注 <?php echo $agent['agent_focusnum'] ?></span>
            <span>员工总数 <?php echo $nurse_count ?></span>
            <span>浏览数 <?php echo $agent['agent_viewnum'] ?></span>
            <span>累计交易 <?php echo $book_count ?></span>
        </div>
        <div class="left agent_code_show">
            <span>机构编号 <?php echo $agent['agent_id'] ?></span>
            <span>有<?php echo $question_count ?>个问题待回答 <a href="index.php?act=agent_question" style="background: #ff6905;color:#fff;padding:0 5px;">回答</a></span>
        </div>
    </div>
</div>
<div id="agent_manage">
    <div id="agent_manage_content">
        <div id="agent_manage_sidebar" class="left">
            <div class="agent_manage_logo">
                <img width="100px" height="100px" src="<?php echo $agent['agent_logo'] ?>" alt="">
            </div>
            <ul class="sidebar_list">
                <li><a href="index.php?act=agent_center">首页编辑</a></li>
                <li><a href="index.php?act=agent_question">机构问答</a></li>
                <li><a href="index.php?act=agent_phone_set">手机设置</a></li>
                <li class="staff_set_list">
                    <a class="list_show">员工管理</a>
                    <ul>
                        <li><a href="index.php?act=agent_nurse_set" style="font-size: 12px;">· 员工设置</a></li>
                        <li><a href="index.php?act=agent_nurse_audit" style="font-size: 12px;">· 审核员工</a></li>
                        <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_nurse_add" style="font-size: 12px;">· 新建员工</a></li>
                    </ul>
                </li>
                <li><a href="index.php?act=agent_book">全部订单</a></li>
                <li><a href="index.php?act=agent_evaluate">评价管理</a></li>
                <li><a href="index.php?act=agent_refund">退款查询</a></li>
                <li><a href="index.php?act=agent_marketing">营销管理</a></li>
                <li><a href="index.php?act=agent_profit">财务中心</a></li>
                <li><a href="index.php?act=agent_invoice">发票管理</a></li>
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
        <div class="agent_manage_point">
            <img style="margin:0 5px 2px 50px;" src="templates/images/warning_mark.png" alt="">新建员工会在1-3个工作日内审核完成，如有疑问，请联系客服
        </div>
        <div style="background: #fff;" id="agent_manage_set" class="left form-list">
            <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />

            <div class="create_basic_information">
                <p><span>快速新建员工</span></p>
                <div class="form-item clearfix">
                    <div class="form-item-value">
                        <label><span class="red">*</span>机构联系方式：</label>
                        <select name="agent_phone" id="agent_phone">
                            <?php foreach ($agent['agent_other_phone_choose'] as $key => $value) { ?>
                                <option value="<?php echo $value ?>"><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <div class="form-item clearfix">
                    <div class="form-item-value">
                        <label><span class="red">*</span>家政人员姓名：</label>
                        <input type="text" id="nurse_name" name="nurse_name" class="form-input w-200" value="">
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <div class="form-item clearfix">
                    <div class="form-item-value" style="height:40px;line-height: 40px;">
                        <label id="nurse_sex"><span class="red">*</span>性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：</label>
                        <span style="margin-right: 20px;">男<input type="radio" class="nurse_sex" name="nurse_sex" value="1"></span>
                        <span>女<input type="radio" class="nurse_sex" name="nurse_sex" value="2"></span>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <div class="form-item clearfix">
                    <div class="form-item-value">
                        <label><span class="red">*</span>家政人员年龄：</label>
                        <input type="text" id="nurse_age" name="nurse_age" class="form-input w-200" value="">
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <div class="form-item clearfix">
                    <div class="form-item-value" style="height:40px;line-height: 40px;">
                        <label><span class="red">*</span>籍&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;贯：</label>
                        <input type="text" id="birth_cityname" name="birth_cityname" class="form-input w-200" value="">
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <div class="form-item clearfix">
                    <div class="form-item-value">
                        <label></label>例如： 2011——2012  在江苏从事安装工
                    </div>
                    <div class="form-item-value" style="height:40px;line-height: 40px;">
                        <label><span class="red"></span>工&nbsp;&nbsp;作&nbsp;&nbsp;经&nbsp;&nbsp;历：</label>
                        <input type="text" class="work_exe form-input w-500" value="">
                    </div>
                    <div class="form-item-value" style="height:40px;line-height: 40px;">
                        <label><span class="red"></span></label>
                        <span class="add_exe_btn" style="color: #ff6905;text-decoration: underline;cursor: pointer;">增加一行</span>
                    </div>
                </div>
                <script>
                    $(".add_exe_btn").click(function () {
                        var html='';
                        html+='<div class="form-item-value" style="height:40px;line-height: 40px;">';
                        html+='<label></label>';
                        html+='<input style="margin-left: 5px;" type="text" class="work_exe form-input w-500" value="">';
                        html+='</div>';
                        $(this).parent().before(html);
                        if($(".work_exe").length>=3){
                            $(".add_exe_btn").hide();
                        }
                    });
                </script>
                <style>
                    #work_type_box{position: fixed;top:8%;right:10%;width:800px;min-height: 400px;background: #fff;z-index: 100;border: 1px solid #ddd;  padding:10px; display: none;}
                    #work_type_box span{ cursor: pointer; font-size: 14px;  display: inline-block;  margin:5px 20px;  border: 1px solid #ccc;  padding:2px 5px;  border-radius: 5px;  }
                    #work_type_box p{font-size: 12px;height:20px;line-height: 20px;margin-left: 20px;font-weight: bold;}
                    #work_type_box span.active{background: #ff6905;  color:#fff;}
                    #work_type_box .work_type_box_menu{  border-top:1px solid #ddd;  padding-top:20px;  text-align: center;  }
                    #work_type_box .create_quxiao{  background: #fff;  border: 1px solid #ddd;  padding:2px 10px;  margin-right: 20px;cursor: pointer;  }
                    #work_type_box .create_queding{  border:none;  padding:2px 10px;  background: #ff6905;  color:#fff;cursor: pointer  }
                </style>
                <div id="work_type_box">
                    <p>职业保姆/涉外保姆</p>
                    <span data="1">住家保姆</span><span data="1">带孩子保姆</span><span data="1">做饭保姆</span>
                    <span data="2">非住家保姆</span><span data="2">涉外保姆</span>
                    <p>钟点服务/清洁清扫</p>
                    <span data="3">接送服务</span><span data="3">买菜做饭</span><span data="3">临时帮工</span><span data="3">钟点看护</span><span data="3">钟点保洁</span><span data="3">综合服务</span><span data="3">散传单</span><span data="3">钟点式家教</span>
                    <span data="4">清洁清扫</span>
                    <p>月嫂保育/育婴早教</p>
                    <span data="5">月子护理师</span><span data="5">产妇照护</span><span disabled="5">催乳师</span>
                    <span data="6">育婴师</span><span data="6">保育员</span><span data="6">婴儿照护</span>
                    <p>水电维修/管道疏通</p>
                    <span data="7">水电安装</span><span data="7">电器维修</span><span data="7">设备安装</span><span data="7">综合保养</span><span data="7">电脑维修</span>
                    <span data="8">管道疏通</span>
                    <p>搬家服务/设备搬运</p>
                    <span data="9">家庭搬运</span><span data="9">企业搬运</span>
                    <span data="10">设备搬运</span><span data="10">综合运输</span>
                    <p>家庭外教/家庭辅导</p>
                    <span data="12">小学家教</span><span data="12">初中家教</span><span data="12">高中家教</span><span data="12">数学家教</span><span data="12">英语家教</span>
                    <span data="12">钢琴家教</span><span data="12">绘画家教</span><span data="12">综合家教</span><span data="12">自考辅导</span><span data="12">其他家教</span>
                    <p>陪护医护/老年照顾</p>
                    <span data="13">康复理疗</span><span data="13">全天医护</span><span data="13">白天医护</span>
                    <span data="14">老幼照顾</span><span data="14">病患照顾</span>
                    <p>管家服务/高级家教</p>
                    <span data="15">高级管家</span><span data="15">空房管家</span><span data="15">别墅管家</span><span data="15">外籍管家</span>
                    <span data="16">成人家教</span>
                    <div class="work_type_box_menu">
                        <button class="create_quxiao">取消</button> <button class="create_queding">确定</button>
                    </div>
                </div>
                <div class="form-item clearfix">
                    <div class="form-item-value" style="height:40px;line-height: 40px;position: relative;">
                        <label><span class="red">*</span>职&nbsp;&nbsp;业&nbsp;&nbsp;分&nbsp;&nbsp;类：</label>
                        <input disabled type="text" id="service_type" name="service_type" class="form-input w-200" value=""> <span class="create_service_type_choose">点击选择</span>
                        <input type="hidden" id="nurse_type" name="nurse_type" value="">
                        <div style="position: absolute;left: 430px;top:14px;" class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <style>
                    #special_service_box{  position: fixed;  top:10%;  right:10%;  width:800px;  min-height:400px;  background: #fff;  z-index: 99;  border: 1px solid #ddd;  padding:10px;  display: none;  }
                    #special_service_box .add_span{  width:300px;  height:26px;  margin-bottom: 10px;  margin-left:350px;  }
                    #special_service_box .add_btn{  height:30px;  }
                    #special_service_box span{  cursor:pointer;font-size: 14px;  display: inline-block;  margin:5px 20px;  border: 1px solid #ccc;  padding:2px 5px;  border-radius: 5px;  }
                    #special_service_box span.active{  background: #ff6905;  color:#fff;  }
                    #special_service_box .special_service_menu{  border-top:1px solid #ddd;  padding-top:20px;  text-align: center;  }
                    #special_service_box .quxiao_btn{  background: #fff;  border: 1px solid #ddd;  padding:2px 10px;  margin-right: 20px;cursor: pointer;  }
                    #special_service_box .queding_btn{  border:none;  padding:2px 10px;  background: #ff6905;  color:#fff; cursor: pointer; }
                    .form-item-value{  position: relative;  }
                </style>
                <div id="special_service_box">
                </div>
                <div class="form-item clearfix">
                        <p style="font-size: 12px;height:20px;margin-left: 110px;line-height: 20px;">注意：特色需求最多可选择四项</p>
                    <div class="form-item-value" style="height:40px;line-height: 40px;position: relative;">
                        <label><span class="red">*</span>特&nbsp;&nbsp;殊&nbsp;&nbsp;服&nbsp;&nbsp;务：</label>
                        <input disabled type="text" id="nurse_special_service" name="nurse_special_service" class="form-input w-500" value=""> <span class="create_special_service_choose">点击选择</span>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <div class="form-item clearfix">
                    <div class="form-item-value">
                        <label><span class="red">*</span>期&nbsp;&nbsp;望&nbsp;&nbsp;薪&nbsp;&nbsp;资：</label>
                        ¥ <input type="text" id="nurse_price" name="nurse_price" class="form-input w-200" value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="price_unit" >每月</span>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <div class="form-item clearfix students_sale_box" style="display: none;">
                    <div class="form-item-value">
                        <label><span class="red"></span>是否允许增加学生（最多4个）：</label>
                        <input type="radio" name="students_state" class="students_state" value="0" checked>&nbsp;&nbsp;&nbsp;<span  >不允许</span>
                        <input type="radio" name="students_state" class="students_state" value="1">&nbsp;&nbsp;&nbsp;<span  >允许</span>
                    </div>
                    <div class="form-item-value student_state_box" style="display: none;">
                        <label><span class="red"></span>多于一个学生优惠：</label>
                        <input type="radio" name="students_sale" class="students_sale" value="0" checked>&nbsp;&nbsp;&nbsp;<span  >全款</span>
                        <input type="radio" name="students_sale" class="students_sale" value="1">&nbsp;&nbsp;&nbsp;<span  >半价</span>
                    </div>
                </div>
                <script>
                    $(".students_state").click(function () {
                        if($(".students_state:checked").val()==1){
                            $(".student_state_box").show();
                        }else{
                            $(".student_state_box").hide();
                        }
                    });
                </script>
                <div class="form-item clearfix car_count_box" style="display: none;">
                    <div class="form-item-value">
                        <label><span class="red"></span>是否提供货车：</label>
                        <input type="radio" name="car_count" class="car_count" value="0" checked>&nbsp;&nbsp;&nbsp;<span >不提供</span>
                        <input type="radio" name="car_count" class="car_count" value="1">&nbsp;&nbsp;&nbsp;<span  >提供</span>
                    </div>
                    <div class="add_car_box" style="display: none;">
                        <div class="form-item-value">
                            <label></label>
                            货车载重 <input type="text" class="car_weight form-input w-50"> 吨 ， 出车费 ¥ <input type="text" class="car_price form-input w-100">
                        </div>
                        <div class="form-item-value">
                            <label></label>
                            <span  class="add_car_btn" style="color: #ff6905;text-decoration: underline;cursor: pointer;">+增加一辆货车</span>
                        </div>
                    </div>
                </div>
                <script>
                    $(".car_count").click(function () {
                        if($(".car_count:checked").val()==1){
                            $(".add_car_box").show();
                        }else{
                            $(".add_car_box").hide();
                        }
                    });
                    $(".add_car_btn").click(function () {
                        var html='';
                        html+='<div class="form-item-value" style="margin-top: 5px;">';
                        html+='<label></label>';
                        html+='货车载重 <input style="margin-left: 5px;" type="text" class="car_weight form-input w-50"> 吨 ， 出车费 ¥ <input type="text" class="car_price form-input w-100">';
                        html+='</div>';
                        $(this).parent().before(html);
                        if($(".car_weight").length>=5){
                            $(".add_car_btn").hide();
                        }
                    });
                </script>
                <div class="form-item clearfix">
                    <div class="form-item-value">
                        <label><span class="red">*</span>个&nbsp;&nbsp;人&nbsp;&nbsp;简&nbsp;&nbsp;介：</label>
                        <textarea class="w-600" name="nurse_content" id="nurse_content" cols="30" rows="10" maxlength="300" placeholder="最多不超过300个字符"></textarea>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <p><span>实名认证</span> <b style="font-size: 12px;margin-left: 20px;">本页面所有图片为 .jpg , .png</b></p>
                <div class="form-item clearfix left" style="margin-right: 20px;overflow: hidden;height:130px;">
                    <label><span class="red">*</span>个人照片：</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <li id="show_image_0" class="cover-item" style="display:none;"></li>
                                <li id="upload_image_0">
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                                            <input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="nurse_image" name="nurse_image" class="image_0" value=""  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-item clearfix left" style="margin-right: 20px;overflow: hidden;height:130px;">
                    <label><span class="red">*</span>身份证正面照：</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <li id="show_image_1" class="cover-item" style="display:none;"></li>
                                <li id="upload_image_1">
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                                            <input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="nurse_cardid_image" name="nurse_cardid_image" class="image_1" value=""  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-item clearfix left" style="height:130px;overflow: hidden;">
                    <label><span class="red">*</span>手持身份证照：</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <li id="show_image_3" class="cover-item" style="display:none;"></li>
                                <li id="upload_image_3">
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                                            <input type="file" id="file_3" name="file_3" field_id="3" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="nurse_cardid_person_image" name="nurse_cardid_person_image" class="image_3" value=""  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <p><span>个人资质</span></p>
                <div class="form-item clearfix">
                    <label>工作资质：</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <li id="show_image_2">
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                            <input type="file" id="file_2" name="file_2" field_id="2" hidefocus="true" maxlength="0" mall_type="image" mode="multi">
                                        </span>
                                    </div>
                                </li>
                            </ul>
                            <p class="help-desc">请上传您的相关工作资质，必须和证件信息相符 （建议不多于4张）</p>
                        </div>
                    </div>
                </div>
                <p><span>服务承诺</span></p>
                <div class="form-item clearfix">
                    <div class="form-item-value">
                        <label><span class="red"></span>服务承诺：</label>
                        <select name="nurse_promise" id="nurse_promise">
                            <option value="0">请选择服务承诺</option>
                            <option value="1">不支持三小时无理由</option>
                            <option value="2">支持三小时无理由</option>
                            <option value="3">不支持三天无理由</option>
                            <option value="4">支持三天无理由</option>
                        </select>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <p><span>注册</span></p>
                <div class="form-item clearfix">
                    <div class="form-item-value">
                        <label><span class="red">*</span>新员工手机号：</label>
                        <input type="text" id="member_phone" name="member_phone" class="form-input w-300" value="">
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <style>
                    .take-code:hover{
                        color:#ff6905;
                    }
                </style>
                <div class="form-item clearfix" style="position: relative;">
                    <div class="form-item-value">
                        <label><span class="red">*</span>获取验证码：</label>
                        <input type="text" id="phone_code" name="phone_code" class="form-input w-300" value="">
                        <span class="take-code" style="position:absolute;left:320px;top:8px;cursor: pointer;">获取手机验证码</span>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
                <div class="form-item clearfix" style="position: relative;">
                    <div class="form-item-value">
                        <label><span class="red">*</span></label>
                        <input type="checkbox" id="agreen_protocol" name="protocol" checked><a href="index.php?act=article&article_id=3">同意&lt;&lt;团家政使用声明&gt;&gt;</a>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </div>
                </div>
            </div>
            <div style="height:200px;line-height: 200px;text-align: center;">
                <span class="create_submit_btn">确认 提交审核</span><span class="return-success"></span>
            </div>
        </div>
    </div>
</div>
<script>
    $("#work_type_box span").click(function () {
        $(this).addClass("active").siblings().removeClass("active");
    });
    $(".create_quxiao").click(function () {
        $("#work_type_box").hide();
    });
    $(".create_service_type_choose").click(function () {
        $("#work_type_box").show();
    });
    $(".create_special_service_choose").click(function () {
        $("#special_service_box").show();
    });
    $(".create_queding").click(function () {
        var item1=$("#work_type_box span.active").text();
        var item2=$("#work_type_box span.active").attr('data');
        var choose_car=$("#work_type_box span.active").attr('data');
        var choose_student=$("#work_type_box span.active").attr('data');
        if(choose_car==9||choose_car==10){
            $(".car_count_box").show();
        }else{
            $(".car_count_box").hide();
        }
        if(choose_student==11 || choose_student==12){
            $(".students_sale_box").show();
        }else{
            $(".students_sale_box").hide();
        }
        if(item2==3){
            $(".price_unit").text('每小时');
        }else if(item2==4){
            $(".price_unit").text('每平方');
        }else if(item2==7 || item2==8){
            $(".price_unit").text('每次');
        }else if(item2==9 || item2==10){
            $(".price_unit").text('每人');
        }else if(item2==11 || item2==12){
            $(".price_unit").text('每月');
        }else{
            $(".price_unit").text('每月');
        }
       $("#service_type").val(item1);
       $("#nurse_type").val(item2);
        $("#work_type_box").hide();
    });
    $('#member_phone').on('blur', function() {
        var member_phone = $('#member_phone').val();
        if(member_phone == '') {
            showerror('member_phone', '手机号必须填写');
            return;
        }
        var regu = /^[1][0-9]{10}$/;
        if(!regu.test(member_phone)) {
            showerror('member_phone', '手机号格式不正确');
            return;
        }
        $.getJSON('index.php?act=register&op=checkname', {'member_phone':member_phone}, function(data){
            if(data.done == 'true') {
                showsuccess('member_phone');
            } else {
                showerror('member_phone', data.msg);
            }
        });
    });
    var code_btn = false;
    $('.take-code').on('click', function() {
        if($('.take-code').hasClass('acquired')) return;
        var member_phone = $('#member_phone').val();
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
            url : 'index.php?act=misc&op=agent_nurse_add_code',
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
    var add_submit_btn = false;
    $(".create_submit_btn").click(function () {
            var formhash = $('#formhash').val();
            var nurse_name = $('#nurse_name').val();
            var agent_phone = $('#agent_phone').val();
            var nurse_sex=$(".nurse_sex:checked").val();
            var nurse_age = $("#nurse_age").val();
            var birth_cityname=$("#birth_cityname").val();
            var nurse_type=$("#nurse_type").val();
            var service_type=$("#service_type").val();
            var nurse_special_service=$("#nurse_special_service").val();
            var nurse_price=$("#nurse_price").val();
            var nurse_content=$("#nurse_content").val();
            var nurse_image = $('#nurse_image').val();
            var nurse_cardid_image = $('#nurse_cardid_image').val();
            var nurse_cardid_person_image=$("#nurse_cardid_person_image").val();
            var member_phone=$("#member_phone").val();
            var phone_code=$("#phone_code").val();
            var promise_state=$("#nurse_promise").val();
            var i = 0;
            var nurse_qa_image = {};
            $('.image_2').each(function() {
                nurse_qa_image[i] = $(this).val();
                i++;
            });
            var j=0;
            var nurse_work_exe={};
            $(".work_exe").each(function () {
               nurse_work_exe[j]=$(this).val();
               j++;
            });
            var car_weight_list={};
            var car_price_list={};
            if($(".car_count:checked").val()==1){
                var a=0;
                $(".car_weight").each(function () {
                    car_weight_list[a]=$(this).val();
                    a++;
                });
                var b=0;
                $(".car_price").each(function () {
                    car_price_list[b]=$(this).val();
                    b++;
                });
            }
            var students_state=0;
            var students_sale=0;
            if($(".students_state:checked").val()==1){
                students_sale=parseInt($(".students_sale:checked").val());
            }
            if(nurse_name == '') {
                $('#nurse_name').focus();
                showerror('nurse_name', '请输入家政人员姓名');
                return;
            }else{
                showsuccess('nurse_name');
            }
            if(nurse_sex==''||nurse_sex==undefined||nurse_sex==0){
                $('#nurse_name').focus();
                showerror('nurse_sex','请选择家政人员性别');
                return;
            }else{
                showsuccess('nurse_sex');
            }
            if(nurse_age==''){
                $("#nurse_age").focus();
                showerror('nurse_age','请输入家政人员年龄');
                return;
            }else{
                showsuccess('nurse_age');
            }
            if(birth_cityname==''){
                $("#birth_cityname").focus();
                showerror('birth_cityname','请输入家政人员籍贯');
                return;
            }else{
                showsuccess('birth_cityname');
            }
            if(service_type == ''||service_type==undefined) {
                $('#birth_cityname').focus();
                showerror('service_type', '请选择可提供服务');
                return;
            }else{
                $("#service_type").siblings(".Validform-checktip").hide();
            }
            if(nurse_price == '') {
                $('#nurse_price').focus();
                showerror('nurse_price', '请输入期望薪资');
                return;
            }else{
                showsuccess('nurse_price');
            }
            if(nurse_content == '') {
                $('#nurse_content').focus();
                showerror('nurse_content', '请输入个人简介');
                return;
            }else {
                showsuccess('nurse_content');
            }
            if(nurse_image == '') {
                $('#nurse_price').focus();
                showerror('nurse_image', '请上传个人照片');
                return;
            }else{
                showsuccess('nurse_image');
            }
            if(nurse_cardid_image == '') {
                showerror('nurse_cardid_image', '请上传身份证正面照');
                return;
            }else{
                showsuccess('nurse_cardid_image');
            }
            if(nurse_cardid_person_image == '') {
                showerror('nurse_cardid_person_image', '请上传手持身份证照');
                return;
            }else{
                showsuccess('nurse_cardid_person_image');
            }
            if(member_phone == '') {
                $('#member_phone').focus();
                showerror('member_phone', '请输入手机号');
                return;
            }else{
                showsuccess('member_phone')
            }
            var regu = /^[1][0-9]{10}$/;
            if(!regu.test(member_phone)) {
                $('#member_phone').focus();
                showerror('member_phone', '手机号格式不正确');
                return;
            }else{
                showsuccess('member_phone');
            }
            if(phone_code==''){
                $('#phone_code').focus();
                showerror('phone_code', '请输入验证码');
                return;
            }else{
                showsuccess('phone_code');
            }
            if(!$("#agreen_protocol").is(":checked")){
                $('#agreen_protocol').focus();
                showerror('agreen_protocol', '必须同意团家政相关协议');
                return;
            }else{
                showsuccess('agreen_protocol');
            }
            var submitData = {
                'form_submit' : 'ok',
                'formhash' : formhash,
                'nurse_name':nurse_name,
                'agent_phone':agent_phone,
                'nurse_sex':nurse_sex,
                'nurse_age':nurse_age,
                'birth_cityname':birth_cityname,
                'nurse_type':nurse_type,
                'service_type':service_type,
                'nurse_special_service':nurse_special_service,
                'nurse_price':nurse_price,
                'nurse_content':nurse_content,
                'nurse_image':nurse_image,
                'nurse_cardid_image':nurse_cardid_image,
                'nurse_cardid_person_image':nurse_cardid_person_image,
                'nurse_qa_image':nurse_qa_image,
                'nurse_work_exe':nurse_work_exe,
                'students_state':students_state,
                'students_sale':students_sale,
                'car_weight_list':car_weight_list,
                'car_price_list':car_price_list,
                'promise_state':promise_state,
                'member_phone':member_phone,
                'phone_code':phone_code
            };
            console.log(submitData);
            if(add_submit_btn) return;
            add_submit_btn = true;
            $.ajax({
                type : 'POST',
                url : 'index.php?act=agent_nurse_add&op=add',
                data : submitData,
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                dataType : 'json',
                success : function(data) {
                    console.log(data);
                    add_submit_btn = false;
                    if(data.done == 'true') {
                        $('.return-success').html('提交成功');
                        $('.return-success').show();
                        setTimeout(function(){
                            window.location.href = 'index.php?act=agent_nurse_add';
                        }, 2000);
                    } else {
                        if(data.id == 'system') {
                            $('.return-success').html(data.msg);
                            $('.return-success').show();
                        } else {
                            showerror(data.id, data.msg);
                        }
                    }
                },
                timeout : 15000,
                error : function(xhr, type){
                    add_submit_btn = false;
                    $('.return-success').html('网络不稳定，请稍后重试');
                    $('.return-success').show();
                }
            });
    });
</script>
<script type="text/javascript">
    var file_name = 'nurse';
</script>
<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="templates/js/imageupload.js"></script>
<script type="text/javascript" src="templates/js/profile/agent_nurse.js"></script>
<script type="text/javascript" src="templates/js/member/type.js"></script>
<script>
    $(".staff_set_list span").click(function () {
        if(!$(".staff_set_list ul").is(":hidden")){
            $(".staff_set_list ul").fadeOut();
            $(".staff_set_list img").attr('src','templates/images/toBW.png');
        }else{
            $(".staff_set_list ul").fadeIn();
            $(".staff_set_list img").attr('src','templates/images/toTopW.png');
        }
    })
</script>
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
