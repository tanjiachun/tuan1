<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
    </style>
    <link rel="stylesheet" href="templates/css/admin.css">
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
            <span>有<?php echo $question_count ?>个问题待回答 <a href="javascript:;" style="background: #ff6905;color:#fff;padding:0 5px;">回答</a></span>
        </div>
    </div>
    </div>
	<div class="conwp">
		<div class="user-main">
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
                        <ul style="display: none;">
                            <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_nurse_set" style="font-size: 12px;">· 员工设置</a></li>
                            <li><a href="index.php?act=agent_nurse_audit" style="font-size: 12px;">· 审核员工</a></li>
                            <li><a href="index.php?act=agent_nurse_add" style="font-size: 12px;">· 新建员工</a></li>
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
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>家政人员简历（部分需审核项修改），本页面信息会在审核完成后更新</strong>
                    <?php if($nurse['revise_state'] == 0) { ?>
                        <div class="edit-box">
                            <p class="b-tips"><span class="red" style="font-size: 24px;">我们将在一天内完成您的信息审核，请耐心等待我们的客服人员联系您</span></p>
                        </div>
                    <?php } elseif($nurse['revise_state'] == 1) { ?>
                        <div class="edit-box">
                            <p class="b-tips"><span class="red" style="font-size: 24px;">您提交的信息已经通过审核</span></p>
                        </div>
                    <?php } elseif($nurse['revise_state'] == 2) { ?>
                        <div class="edit-box">
                            <p class="b-tips"><span class="red" style="font-size: 24px;">抱歉，您的审核不通过，请确认您的信息正确与完整</span></p>
                        </div>
                    <?php } ?>
				</div>
				<div class="edit-box">
					<div class="edit-body">
						<div class="edit-body-title">基础信息</div>
						<div class="edit-body-con">
							<div class="form-list">
								<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
								<input type="hidden" id="nurse_id" name="nurse_id" value="<?php echo $nurse['nurse_id'];?>" />
								<div class="form-item clearfix">
									<label><span class="red">*</span>家政人员姓名：</label>
									<div class="form-item-value">
										<input type="text" id="nurse_name" name="nurse_name" class="form-input w-200" value="<?php echo $nurse['nurse_name'];?>">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label><span class="red">*</span>家政人员手机：</label>
									<div class="form-item-value">
										<input type="text" id="nurse_phone" name="nurse_phone" class="form-input w-200" value="<?php echo $nurse['member_phone'];?>">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
                                <div class="form-item clearfix">
                                    <label><span class="red">*</span>看护类别：</label>
                                    <div class="form-item-value">
                                        <div class="selectBox" style="width:700px;min-height:100px;border:1px solid #ddd;border-radius: 10px;">
                                            <span></span>
                                        </div>
                                        <input type="hidden" id="nurse_type" name="nurse_type" value="">
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                </div>
                                <style>
                                    .keywordBox span,.selectBox span{display: inline-block;padding:0 5px;border:1px solid ;border-radius:5px;line-height: 30px;text-align: center;margin:10px 10px;}
                                    .keywordBox span:hover,.selectBox span:hover{border-color:#2a90e2;color:red;}
                                    .on{background:#2a90e2;color:#fff; }
                                </style>
                                <div class="form-item clearfix">
                                    <label><span class="red">*</span>可提供服务：</label>
                                    <div class="form-item-value">
                                        <div class="keywordBox" style="width:700px;min-height:100px;border:1px solid #ddd;border-radius: 10px;">
                                        </div>
                                        <input type="hidden" id="service_type" name="service_type" value="">
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                </div>
                                <script>
                                    var nurse_type = '<?php echo $nurse['nurse_type'];?>';
                                    var service_type = '<?php echo $nurse['service_type'];?>';
                                </script>
                                <div class="form-item clearfix">
                                    <p>点击文本框后面选择, <b>( 最多可选择4个)</b></p>
                                    <label><span class="red">*</span>特色需求：</label>
                                    <div class="form-item-value">
                                        <input type="text" id="nurse_special_service" disabled name="nurse_special_service" class="form-input w-600" value="<?php echo $nurse['nurse_special_service'];?>"> <span class="special_service_show">点击选择</span>
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                </div>
                                <style>
                                    #special_service_box{
                                        position: fixed;
                                        top:10%;
                                        right:10%;
                                        width:800px;
                                        min-height:400px;
                                        background: #fff;
                                        z-index: 99;
                                        border: 1px solid #ddd;
                                        padding:10px;
                                        display: none;
                                    }
                                    #special_service_box .add_span{
                                        width:300px;
                                        height:26px;
                                        margin-bottom: 10px;
                                        margin-left:350px;
                                    }
                                    #special_service_box .add_btn{
                                        height:30px;
                                    }
                                    #special_service_box span{
                                        font-size: 14px;
                                        display: inline-block;
                                        margin:5px 20px;
                                        border: 1px solid #ccc;
                                        padding:2px 5px;
                                        border-radius: 5px;
                                    }
                                    #special_service_box span.active{
                                        background: #ff6905;
                                        color:#fff;
                                    }
                                    #special_service_box .special_service_menu{
                                        border-top:1px solid #ddd;
                                        padding-top:20px;
                                        text-align: center;
                                    }
                                    #special_service_box .quxiao_btn{
                                        background: #fff;
                                        border: 1px solid #ddd;
                                        padding:2px 10px;
                                        margin-right: 20px;
                                    }
                                    #special_service_box .queding_btn{
                                        border:none;
                                        padding:2px 10px;
                                        background: #ff6905;
                                        color:#fff;
                                    }
                                    .form-item-value{
                                        position: relative;
                                    }
                                    .special_service_show{
                                        position: absolute;
                                        top:2px;
                                        height:25px;
                                        line-height: 25px;
                                        border: 1px solid #ddd;
                                        padding:4px 10px;
                                        cursor: pointer;
                                    }
                                    .special_service_show:hover{
                                        color:#FF6905;
                                    }
                                </style>
                                <div id="special_service_box">
                                </div>
                                <div class="form-item clearfix">
									<label><span class="red">*</span>家政人员年龄：</label>
									<div class="form-item-value">
										<input type="text" id="nurse_age" name="nurse_age" class="form-input w-100" value="<?php echo $nurse['nurse_age'];?>">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
                                <div class="form-item clearfix">
									<label><span class="red">*</span>出生地址：</label>
									<div class="form-item-value">
                                    	<div class="first-province-box" prefix="birth" style="display:inline-block">
                                            <div class="select-class">
                                                <a href="javascript:;" class="select-choice"><?php echo !empty($birth_provincename) ? $birth_provincename : '-省份-';?><i class="select-arrow"></i></a>
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
                                        <div class="first-city-box" style="display:inline-block">
											<?php if(!empty($birth_city_list)) { ?>
                                        	<div class="select-class">
                                                <a href="javascript:;" class="select-choice"><?php echo !empty($birth_cityname) ? $birth_cityname : '-城市-';?><i class="select-arrow"></i></a>
                                                <div class="select-list" style="display: none">
                                                    <ul>
                                                        <li field_value="">-城市-</li>
                                                        <?php foreach($birth_city_list as $key => $value) { ?>
                                                        <li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
											<?php } ?>
                                        </div>
                                        <div class="first-area-box" style="display:inline-block">
                                        	<?php if(!empty($birth_area_list)) { ?>
                                        	<div class="select-class">
                                                <a href="javascript:;" class="select-choice"><?php echo !empty($birth_areaname) ? $birth_areaname : '-州县-';?><i class="select-arrow"></i></a>
                                                <div class="select-list" style="display: none">
                                                    <ul>
                                                        <li field_value="">-州县-</li>
                                                        <?php foreach($birth_area_list as $key => $value) { ?>
                                                        <li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <input type="hidden" id="birth_provinceid" name="birth_provinceid" value="<?php echo $birth_provinceid;?>" />
										<input type="hidden" id="birth_cityid" name="birth_cityid" value="<?php echo $birth_cityid;?>" />
										<input type="hidden" id="birth_areaid" name="birth_areaid" value="<?php echo $birth_areaid;?>" />
                                        <div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
                                <div class="form-item clearfix">
									<label><span class="red">*</span>现居地址：</label>
									<div class="form-item-value">
										<div class="second-province-box" prefix="nurse" style="display:inline-block">
                                            <div class="select-class">
                                                <a href="javascript:;" class="select-choice"><?php echo !empty($nurse_provincename) ? $nurse_provincename : '-省份-';?><i class="select-arrow"></i></a>
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
                                        	<?php if(!empty($nurse_city_list)) { ?>
                                            <div class="select-class">
                                                <a href="javascript:;" class="select-choice"><?php echo !empty($nurse_cityname) ? $nurse_cityname : '-城市-';?><i class="select-arrow"></i></a>
                                                <div class="select-list" style="display: none">
                                                    <ul>
                                                        <li field_value="">-城市-</li>
                                                        <?php foreach($nurse_city_list as $key => $value) { ?>
                                                        <li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
											<?php } ?>
                                        </div>
                                        <div class="second-area-box" style="display:inline-block">
                                        	<?php if(!empty($nurse_area_list)) { ?>
                                            <div class="select-class">
                                                <a href="javascript:;" class="select-choice"><?php echo !empty($nurse_areaname) ? $nurse_areaname : '-州县-';?><i class="select-arrow"></i></a>
                                                <div class="select-list" style="display: none">
                                                    <ul>
                                                        <li field_value="">-州县-</li>
                                                        <?php foreach($nurse_area_list as $key => $value) { ?>
                                                        <li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <input type="hidden" id="nurse_provinceid" name="nurse_provinceid" value="<?php echo $nurse_provinceid;?>" />
										<input type="hidden" id="nurse_cityid" name="nurse_cityid" value="<?php echo $nurse_cityid;?>" />
										<input type="hidden" id="nurse_areaid" name="nurse_areaid" value="<?php echo $nurse_areaid;?>" />
                                        <div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
                                <div class="form-item clearfix">
									<label><span class="red">*</span>详细地址：</label>
									<div class="form-item-value">
										<input type="text" id="nurse_address" name="nurse_address" class="form-input w-400" value="<?php echo $nurse['nurse_address'];?>">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
                                <div class="form-item clearfix">
									<label><span class="red">*</span>工作年限：</label>
									<div class="form-item-value">
										<input type="text" id="nurse_education" name="nurse_education" class="form-input w-100" value="<?php echo $nurse['nurse_education'];?>"> 年
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
                                <div class="form-item clearfix">
                                        <label><span class="red">*</span><span id="price_field">期望薪资</span>：</label>
                                    <div class="form-item-value">
                                        <input type="text" id="nurse_price" name="nurse_price" class="form-input w-100" value="<?php echo $nurse['nurse_price'];?>">
                                        <span class="price_unit">每月</span>
                                        <div class="Validform-checktip Validform-wrong"></div>
                                    </div>
                                </div>
<!--                                <div class="form-item clearfix students_sale_box" style="display: none;">-->
<!--                                    <div class="form-item-value">-->
<!--                                        <label style="width:210px;"><span class="red"></span>是否允许增加学生（最多4个）：</label>-->
<!--                                        <input type="radio" name="students_state" class="students_state" value="0" checked>&nbsp;&nbsp;&nbsp;<span  >不允许</span>-->
<!--                                        <input type="radio" name="students_state" class="students_state" value="1">&nbsp;&nbsp;&nbsp;<span  >允许</span>-->
<!--                                    </div>-->
<!--                                    <div class="form-item-value student_state_box" style="display: none;">-->
<!--                                        <label style="width:150px;"><span class="red"></span>多于一个学生优惠：</label>-->
<!--                                        <input type="radio" name="students_sale" class="students_sale" value="0" checked>&nbsp;&nbsp;&nbsp;<span  >全款</span>-->
<!--                                        <input type="radio" name="students_sale" class="students_sale" value="1">&nbsp;&nbsp;&nbsp;<span  >半价</span>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <script>-->
<!--                                    $(".students_state").click(function () {-->
<!--                                        if($(".students_state:checked").val()==1){-->
<!--                                            $(".student_state_box").show();-->
<!--                                        }else{-->
<!--                                            $(".student_state_box").hide();-->
<!--                                        }-->
<!--                                    });-->
<!--                                </script>-->
<!--                                <div class="form-item clearfix car_count_box" style="display: none;">-->
<!--                                    <div class="form-item-value">-->
<!--                                        <label><span class="red"></span>是否提供货车：</label>-->
<!--                                        <input type="radio" name="car_count" class="car_count" value="0" checked>&nbsp;&nbsp;&nbsp;<span >不提供</span>-->
<!--                                        <input type="radio" name="car_count" class="car_count" value="1">&nbsp;&nbsp;&nbsp;<span  >提供</span>-->
<!--                                    </div>-->
<!--                                    <div class="add_car_box" style="display: none;">-->
<!--                                        <div class="form-item-value">-->
<!--                                            <label></label>-->
<!--                                            货车载重 <input type="text" class="car_weight form-input w-50"> 吨 ， 出车费 ¥ <input type="text" class="car_price form-input w-100">-->
<!--                                        </div>-->
<!--                                        <div class="form-item-value">-->
<!--                                            <label></label>-->
<!--                                            <span  class="add_car_btn" style="color: #ff6905;text-decoration: underline;cursor: pointer;">+增加一辆货车</span>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <script>-->
<!--                                    $(".car_count").click(function () {-->
<!--                                        if($(".car_count:checked").val()==1){-->
<!--                                            $(".add_car_box").show();-->
<!--                                        }else{-->
<!--                                            $(".add_car_box").hide();-->
<!--                                        }-->
<!--                                    });-->
<!--                                    $(".add_car_btn").click(function () {-->
<!--                                        var html='';-->
<!--                                        html+='<div class="form-item-value" style="margin-top: 5px;">';-->
<!--                                        html+='<label></label>';-->
<!--                                        html+='货车载重 <input style="margin-left: 5px;" type="text" class="car_weight form-input w-50"> 吨 ， 出车费 ¥ <input type="text" class="car_price form-input w-100">';-->
<!--                                        html+='</div>';-->
<!--                                        $(this).parent().before(html);-->
<!--                                        if($(".car_weight").length>=5){-->
<!--                                            $(".add_car_btn").hide();-->
<!--                                        }-->
<!--                                    });-->
<!--                                </script>-->
							</div>
						</div>
						<div class="edit-body-title">详情描述</div>
						<div class="edit-body-con">
							<div class="form-list">
                            	<div class="form-item clearfix">
									<label><span class="red">*</span>个人照片：</label>
									<div class="form-item-value">
										<div class="picture-list">
                                            <ul class="clearfix">
                                            	<?php if(!empty($nurse['nurse_image'])) { ?>
                                                <li id="show_image_0" class="cover-item">
                                                	<img src="<?php echo $nurse['nurse_image'];?>">
                                                    <span class="close-modal single_close" field_id="0">×</span>
                                                </li>
                                                <?php } else { ?>
                                                <li id="show_image_0" class="cover-item" style="display:none;"></li>
                                                <?php } ?>
                                                <li id="upload_image_0"<?php echo !empty($nurse['nurse_image']) ? ' style="display:none;"' : '';?>>
                                                    <div class="img-update">
                                                        <span class="img-layer">+ 上传</span>
                                                        <span class="img-file">
                                                            <input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                                        </span>
                                                    </div>
                                                </li>
                                                <input type="hidden" id="nurse_image" name="nurse_image" class="image_0" value="<?php echo $nurse['nurse_image'];?>"  />
                                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                                            </ul>
                                        </div>
									</div>
								</div>
                               	<div class="form-item clearfix">
									<label><span class="red">*</span>身份证号码：</label>
									<div class="form-item-value">
										<input id="nurse_cardid" name="nurse_cardid" class="form-input w-400" type="text" value="<?php echo $nurse['nurse_cardid'];?>">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label><span class="red">*</span>手持身份证照：</label>
									<div class="form-item-value">
										<div class="picture-list">
                                            <ul class="clearfix">
                                            	<?php if(!empty($nurse['nurse_cardid_image'])) { ?>
                                                <li id="show_image_1" class="cover-item">
                                                	<img src="<?php echo $nurse['nurse_cardid_image'];?>">
                                                    <span class="close-modal single_close" field_id="1">×</span>
                                                </li>
                                                <?php } else { ?>
                                                <li id="show_image_1" class="cover-item" style="display:none;"></li>
                                                <?php } ?>
                                                <li id="upload_image_1"<?php echo !empty($nurse['nurse_cardid_image']) ? ' style="display:none;"' : '';?>>
                                                    <div class="img-update">
                                                        <span class="img-layer">+ 上传</span>
                                                        <span class="img-file">
                                                            <input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                                        </span>
                                                    </div>
                                                </li>
                                                <input type="hidden" id="nurse_cardid_image" name="nurse_cardid_image" class="image_1" value="<?php echo $nurse['nurse_cardid_image'];?>"  />
                                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                                            </ul>
                                        </div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label>工作资质：</label>
									<div class="form-item-value">
										<div class="picture-list">
                                            <ul class="clearfix">
                                            	<?php foreach($nurse['nurse_qa_image'] as $key => $value) { ?>
                                            	<li class="cover-item">
                                                	<img src="<?php echo $value;?>">
                                                    <span class="close-modal multi_close">×</span>
                                                    <input type="hidden" name="image_2[]" class="image_2" value="<?php echo $value;?>">
                                                </li>
                                                <?php } ?>
                                                <li id="show_image_2">
                                                    <div class="img-update">
                                                        <span class="img-layer">+ 上传</span>
                                                        <span class="img-file">
                                                            <input type="file" id="file_2" name="file_2" field_id="2" hidefocus="true" maxlength="0" mall_type="image" mode="multi">
                                                        </span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <p class="help-desc">请上传您的相关工作资质，必须和证件信息相符</p>
                                        </div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label><span class="red">*</span>服务项目：</label>
									<div class="form-item-value">
										<textarea class="form-textarea w-10-9" id="nurse_content" name="nurse_content" rows="10"><?php echo $nurse['nurse_content'];?></textarea>
                                        <div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
							</div>
							<div class="form-list">
								<div class="form-item clearfix">
									<label>&nbsp;</label>
									<div class="form-item-value">
										<a href="javascript:editsubmit();" class="btn btn-primary">保存</a><span class="return-success"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var file_name = 'nurse';
	</script>
    <script>
        $(".selectBox").on('click','span',function () {
//            var item1=$(this).text();
            var item2=$(this).attr('filed_value');
//            var choose_car=$(this).attr('filed_value');
//            var choose_student=$(this).attr('filed_value');
//            console.log(item2);
//            if(choose_car==9||choose_car==10){
//                $(".car_count_box").show();
//            }else{
//                $(".car_count_box").hide();
//            }
//            if(choose_student==11 || choose_student==12){
//                $(".students_sale_box").show();
//            }else{
//                $(".students_sale_box").hide();
//            }
            if(item2==3){
                $(".price_unit").text('每小时');
            }else if(item2==4){
                $(".price_unit").text('每平方');
            }else if(item2==7 || item2==8){
                $(".price_unit").text('每次');
            }else if(item2==9 || item2==10){
                $(".price_unit").text('每次');
            }else if(item2==11 || item2==12){
                $(".price_unit").text('每月');
            }else{
                $(".price_unit").text('每月');
            }
//            $("#service_type").val(item1);
//            $("#nurse_type").val(item2);
//            $("#work_type_box").hide();
        })
    </script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="templates/js/imageupload.js"></script>
	<script type="text/javascript" src="templates/js/profile/agent_nurse.js"></script>
    <script type="text/javascript" src="templates/js/member/type.js"></script>
<?php include(template('common_footer'));?>