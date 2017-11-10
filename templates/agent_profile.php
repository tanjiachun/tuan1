<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="index.php?act=agent&op=login"><i class="iconfont icon-user"></i>家政机构</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=agent_book">订单管理</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=agent_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">家政人员中心</h3>
					<ul>
						<li><a href="index.php?act=agent_nurse">家政人员管理</a></li>
					</ul>
					<h3 class="no4">机构设置</h3>
					<ul>
						<li><a class="active" href="index.php?act=agent_profile">机构信息</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>机构信息</strong>
				</div>
				<div class="user-info">
					<div class="form-list">
						<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                        <div class="form-item clearfix">
							<label>机构名称：</label>
							<div class="form-item-value">
								<input type="text" id="agent_name" name="agent_name" value="<?php echo $this->agent['agent_name'];?>" class="form-input" placeholder="输入你的机构名称">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>机构LOGO：</label>
							<div class="form-item-value">
								<div class="picture-list">
									<ul class="clearfix">
										<?php if(!empty($this->agent['agent_logo'])) { ?>
										<li id="show_image_0" class="cover-item">
											<img src="<?php echo $this->agent['agent_logo'];?>">
											<span class="close-modal single_close" field_id="0">×</span>
										</li>
										<?php } else { ?>
										<li id="show_image_0" class="cover-item" style="display:none;"></li>
										<?php } ?>
										<li id="upload_image_0">
											<div class="img-update">
												<span class="img-layer">+ 上传</span>
												<span class="img-file">
													<input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="single">
												</span>
											</div>
										</li>
										<input type="hidden" id="agent_logo" name="agent_logo" class="image_0" value="<?php echo $this->agent['agent_logo'];?>"  />
										<div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
									</ul>
								</div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>机构横幅：</label>
							<div class="form-item-value">
								<div class="picture-list">
									<ul class="clearfix">
										<?php if(!empty($this->agent['agent_banner'])) { ?>
										<li id="show_image_1" class="cover-item">
											<img src="<?php echo $this->agent['agent_banner'];?>">
											<span class="close-modal single_close" field_id="1">×</span>
										</li>
										<?php } else { ?>
										<li id="show_image_1" class="cover-item" style="display:none;"></li>
										<?php } ?>
										<li id="upload_image_1">
											<div class="img-update">
												<span class="img-layer">+ 上传</span>
												<span class="img-file">
													<input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="single">
												</span>
											</div>
										</li>
										<input type="hidden" id="agent_banner" name="agent_banner" class="image_1" value="<?php echo $this->agent['agent_banner'];?>"  />
										<div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
									</ul>
								</div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>客服QQ：</label>
							<div class="form-item-value">
								<input type="text" id="agent_qq" name="agent_qq" value="<?php echo $this->agent['agent_qq'];?>" class="form-input" placeholder="输入你的客服QQ">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>联系电话：</label>
							<div class="form-item-value">
								<input type="text" id="agent_phone" name="agent_phone" value="<?php echo $this->agent['agent_phone'];?>" class="form-input" placeholder="输入你的联系电话">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>所在地区：</label>
							<div class="form-item-value">
								<div class="first-province-box" prefix="agent" style="display:inline-block">
									<div class="select-class">
										<a href="javascript:;" class="select-choice"><?php echo !empty($agent_provincename) ? $agent_provincename : '-省份-';?><i class="select-arrow"></i></a>
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
									<?php if(!empty($agent_city_list)) { ?>
									<div class="select-class">
										<a href="javascript:;" class="select-choice"><?php echo !empty($agent_cityname) ? $agent_cityname : '-城市-';?><i class="select-arrow"></i></a>
										<div class="select-list" style="display: none">
											<ul>
												<li field_value="">-城市-</li>
												<?php foreach($agent_city_list as $key => $value) { ?>
												<li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
									<?php } ?>
								</div>
								<div class="first-area-box" style="display:inline-block">
									<?php if(!empty($agent_area_list)) { ?>
									<div class="select-class">
										<a href="javascript:;" class="select-choice"><?php echo !empty($agent_areaname) ? $agent_areaname : '-州县-';?><i class="select-arrow"></i></a>
										<div class="select-list" style="display: none">
											<ul>
												<li field_value="">-州县-</li>
												<?php foreach($agent_area_list as $key => $value) { ?>
												<li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
									<?php } ?>
								</div>
								<input type="hidden" id="agent_provinceid" name="agent_provinceid" value="<?php echo $agent_provinceid;?>" />
								<input type="hidden" id="agent_cityid" name="agent_cityid" value="<?php echo $agent_cityid;?>" />
								<input type="hidden" id="agent_areaid" name="agent_areaid" value="<?php echo $agent_areaid;?>" />
								<div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>详细地址：</label>
							<div class="form-item-value">
								<input type="text" id="agent_address" name="agent_address" class="form-input w-400" value="<?php echo $this->agent['agent_address'];?>">
								<div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
                            <label>服务描述：</label>
                            <div class="form-item-value">
                                <textarea class="form-textarea w-10-9" id="agent_content" name="agent_content" rows="10"><?php echo $this->agent['agent_content'];?></textarea>
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
	<script type="text/javascript">
		var file_name = 'agent';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>   	
    <script type="text/javascript" src="templates/js/imageupload.js"></script>
	<script type="text/javascript" src="templates/js/profile/agent_profile.js"></script>
<?php include(template('common_footer'));?>