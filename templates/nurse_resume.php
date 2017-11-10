<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>家政人员中心</a></h1>
                    <h3 class="no1">简历管理</h3>
					<ul>
						<li><a class="active" href="index.php?act=nurse_resume">我的简历</a></li>
					</ul>
					<h3 class="no2">工作管理</h3>
					<ul>
						<li><a href="index.php?act=nurse_book">我的工作</a></li>
					</ul>
					<h3 class="no3">资产中心</h3>
					<ul>
						<li><a href="index.php?act=nurse_profit">我的收益</a></li>
					</ul>
					<h3 class="no4">账户设置</h3>
					<ul>
						<li><a href="index.php?act=nurse_password">密码修改</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>我的简历</strong>
				</div>
                <?php if($this->nurse['nurse_state'] == 0) { ?>
                <div class="edit-box">
                	<p class="b-tips"><span class="red" style="font-size: 24px;">我们将在一天内完成您的信息审核，请耐心等待我们的客服人员联系您</span></p>
                </div>
                <?php } elseif($this->nurse['nurse_state'] == 1) { ?>
                <div class="edit-box">
                	<p class="b-tips"><span class="red" style="font-size: 24px;">您的简历已经通过审核，雇主已经可以看到您啦</span></p>
                </div>
                <?php } elseif($this->nurse['nurse_state'] == 2) { ?>
                <div class="edit-box">
                	<p class="b-tips"><span class="red" style="font-size: 24px;">抱歉，您的审核不通过，请确认您的信息正确完整</span></p>
                </div>
				<?php } ?>
				<div class="edit-box">
					<div class="edit-body">
						<div class="edit-body-title">基础信息</div>
						<div class="edit-body-con">
							<div class="form-list">
								<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
								<div class="form-item clearfix">
									<label><span class="red">*</span>您的姓名：</label>
									<div class="form-item-value">
										<input type="text" id="nurse_name" name="nurse_name" class="form-input w-200" value="<?php echo $this->nurse['nurse_name'];?>">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label><span class="red">*</span>您的手机号：</label>
									<div class="form-item-value">
										<input type="text" id="member_phone" name="member_phone" class="form-input w-200" value="<?php echo $this->nurse['member_phone'];?>">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label><span class="red">*</span>看护类别：</label>
									<div class="form-item-value">
                                        <div class="selectBox" style="width:700px;min-height:100px;border:1px solid #ddd;border-radius: 10px;">
                                            <span></span>
                                        </div>
                                        <input type="hidden" id="nurse_type" name="nurse_type" value="<?php echo $this->nurse['nurse_type'];?>">
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
                                    var nurse_type = '<?php echo $this->nurse['nurse_type'];?>';
                                    var service_type = '<?php echo $this->nurse['service_type'];?>';

                                </script>
                                <div class="form-item clearfix">
									<label><span class="red">*</span>您的年龄：</label>
									<div class="form-item-value">
										<input type="text" id="nurse_age" name="nurse_age" class="form-input w-100" value="<?php echo $this->nurse['nurse_age'];?>">
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
										<input type="text" id="nurse_address" name="nurse_address" class="form-input w-400" value="<?php echo $this->nurse['nurse_address'];?>">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label><span class="red">*</span>工作年限：</label>
									<div class="form-item-value">
										<input type="text" id="nurse_education" name="nurse_education" class="form-input w-100" value="<?php echo $this->nurse['nurse_education'];?>"> 年
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
								<div class="form-item clearfix">
									<?php if($this->nurse['nurse_type'] == 4) { ?>
									<label><span class="red">*</span><span id="price_field">期望时薪</span>：</label>
									<?php } else { ?>
									<label><span class="red">*</span><span id="price_field">期望月薪</span>：</label>
									<?php } ?>
									<div class="form-item-value">
										<input type="text" id="nurse_price" name="nurse_price" class="form-input w-100" value="<?php echo $this->nurse['nurse_price'];?>"> 元
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
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
                                            	<?php if(!empty($this->nurse['nurse_image'])) { ?>
                                                <li id="show_image_0" class="cover-item">
                                                	<img src="<?php echo $this->nurse['nurse_image'];?>">
                                                    <span class="close-modal single_close" field_id="0">×</span>
                                                </li>
                                                <?php } else { ?>
                                                <li id="show_image_0" class="cover-item" style="display:none;"></li>
                                                <?php } ?>
                                                <li id="upload_image_0"<?php echo !empty($this->nurse['nurse_image']) ? ' style="display:none;"' : '';?>>
                                                    <div class="img-update">
                                                        <span class="img-layer">+ 上传</span>
                                                        <span class="img-file">
                                                            <input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                                        </span>
                                                    </div>
                                                </li>
                                                <input type="hidden" id="nurse_image" name="nurse_image" class="image_0" value="<?php echo $this->nurse['nurse_image'];?>"  />
                                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                                            </ul>
                                        </div>
									</div>
								</div>
                                <div class="form-item clearfix">
									<label><span class="red">*</span>身份证号码：</label>
									<div class="form-item-value">
										<input id="nurse_cardid" name="nurse_cardid" class="form-input w-400" type="text" value="<?php echo $this->nurse['nurse_cardid'];?>">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label><span class="red">*</span>手持身份证照：</label>
									<div class="form-item-value">
										<div class="picture-list">
                                            <ul class="clearfix">
                                            	<?php if(!empty($this->nurse['nurse_cardid_image'])) { ?>
                                                <li id="show_image_1" class="cover-item">
                                                	<img src="<?php echo $this->nurse['nurse_cardid_image'];?>">
                                                    <span class="close-modal single_close" field_id="1">×</span>
                                                </li>
                                                <?php } else { ?>
                                                <li id="show_image_1" class="cover-item" style="display:none;"></li>
                                                <?php } ?>
                                                <li id="upload_image_1"<?php echo !empty($this->nurse['nurse_cardid_image']) ? ' style="display:none;"' : '';?>>
                                                    <div class="img-update">
                                                        <span class="img-layer">+ 上传</span>
                                                        <span class="img-file">
                                                            <input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                                        </span>
                                                    </div>
                                                </li>
                                                <input type="hidden" id="nurse_cardid_image" name="nurse_cardid_image" class="image_1" value="<?php echo $this->nurse['nurse_cardid_image'];?>"  />
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
                                            	<?php foreach($this->nurse['nurse_qa_image'] as $key => $value) { ?>
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
										<textarea class="form-textarea w-10-9" id="nurse_content" name="nurse_content" rows="10"><?php echo $this->nurse['nurse_content'];?></textarea>
                                        <div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
							</div>
							<div class="form-list">
								<div class="form-item clearfix">
									<label>&nbsp;</label>
									<div class="form-item-value">
										<a href="javascript:checksubmit();" class="btn btn-primary">发布</a><span class="return-success">保存成功</span>
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
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>	
    <script type="text/javascript" src="templates/js/imageupload.js"></script>
	<script type="text/javascript" src="templates/js/profile/nurse_resume.js"></script>
    <script type="text/javascript" src="templates/js/member/type.js"></script>
<?php include(template('common_footer'));?>