<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>养老机构</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=pension_bespoke">订单管理</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=pension_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">机构配套</h3>
					<ul>
						<li><a href="index.php?act=pension_room">房间管理</a></li>
					</ul>
					<h3 class="no4">机构设置</h3>
					<ul>
						<li><a class="active" href="index.php?act=pension_profile">机构信息</a></li>
						<li><a href="index.php?act=pension_profile&op=near">机构周边</a></li>
                        <li><a href="index.php?act=pension_profile&op=equipment">机构设施</a></li>
                        <li><a href="index.php?act=pension_profile&op=service">机构服务</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="user-info">
					<div class="form-list">
						<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                        <div class="form-item clearfix">
							<label>机构名称：</label>
							<div class="form-item-value">
								<input type="text" id="pension_name" name="pension_name" value="<?php echo $this->pension['pension_name'];?>" class="form-input" placeholder="输入你的机构名称">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>机构LOGO：</label>
							<div class="form-item-value">
								<div class="picture-list">
									<ul class="clearfix">
										<?php if(!empty($this->pension['pension_logo'])) { ?>
										<li id="show_image_2" class="cover-item">
											<img src="<?php echo $this->pension['pension_logo'];?>">
											<span class="close-modal single_close" field_id="2">×</span>
										</li>
										<?php } else { ?>
										<li id="show_image_2" class="cover-item" style="display:none;"></li>
										<?php } ?>
										<li id="upload_image_2">
											<div class="img-update">
												<span class="img-layer">+ 上传</span>
												<span class="img-file">
													<input type="file" id="file_2" name="file_2" field_id="2" hidefocus="true" maxlength="0" mall_type="image" mode="single">
												</span>
											</div>
										</li>
										<input type="hidden" id="pension_logo" name="pension_logo" class="image_2" value="<?php echo $this->pension['pension_logo'];?>"  />
										<div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
									</ul>
								</div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>机构封面：</label>
							<div class="form-item-value">
								<div class="picture-list">
									<ul class="clearfix">
										<?php if(!empty($this->pension['pension_image'])) { ?>
										<li id="show_image_0" class="cover-item">
											<img src="<?php echo $this->pension['pension_image'];?>">
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
										<input type="hidden" id="pension_image" name="pension_image" class="image_0" value="<?php echo $this->pension['pension_image'];?>"  />
										<div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
									</ul>
								</div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>机构图片：</label>
							<div class="form-item-value">
								<div class="picture-list">
									<ul class="clearfix">
										<?php foreach($this->pension['pension_image_more'] as $key => $value) { ?>
										<li class="cover-item">
											<img src="<?php echo $value;?>">
											<span class="close-modal multi_close">×</span>
											<input type="hidden" name="imsge_1[]" class="image_1" value="<?php echo $value;?>">
										</li>
										<?php } ?>
										<li id="show_image_1">
											<div class="img-update">
												<span class="img-layer">+ 上传</span>
												<span class="img-file">
													<input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="multi">
												</span>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
                        <div class="form-item clearfix">
                            <label>机构类型：</label>
                            <div class="form-item-value">
                                <div class="select-class select-box">
                                    <a href="javascript:;" class="select-choice"><?php echo !empty($this->pension['pension_type']) ? $type_array[$this->pension['pension_type']] : '请选择';?><i class="select-arrow"></i></a>
                                    <div class="select-list" style="display: none">
                                        <ul class="class-box">
                                            <li field_value="" field_key="pension_type">请选择</li>
                                            <li field_value="1" field_key="pension_type">养老产业园</li>
                                            <li field_value="2" field_key="pension_type">老年公寓</li>
                                            <li field_value="3" field_key="pension_type">护理院</li>
                                            <li field_value="4" field_key="pension_type">托老所</li>
                                            <li field_value="5" field_key="pension_type">养老院</li>
                                            <li field_value="6" field_key="pension_type">敬老院</li>
                                        </ul>
                                    </div>
                                </div>
                                <input type="hidden" id="pension_type" name="pension_type" value="<?php echo $this->pension['pension_type'];?>">
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
                            <label>机构性质：</label>
                            <div class="form-item-value">
                                <div class="select-class select-box">
                                    <a href="javascript:;" class="select-choice"><?php echo !empty($this->pension['pension_nature']) ? $nature_array[$this->pension['pension_nature']] : '请选择';?><i class="select-arrow"></i></a>
                                    <div class="select-list" style="display: none">
                                        <ul class="class-box">
                                            <li field_value="" field_key="pension_nature">请选择</li>
                                            <li field_value="1" field_key="pension_nature">民办</li>
                                            <li field_value="2" field_key="pension_nature">公办</li>
                                        </ul>
                                    </div>
                                </div>
                                <input type="hidden" id="pension_nature" name="pension_nature" value="<?php echo $this->pension['pension_nature'];?>">
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
                            <label>收住对象：</label>
                            <div class="form-item-value">
                                <div class="select-class select-box">
                                    <a href="javascript:;" class="select-choice"><?php echo !empty($this->pension['pension_person']) ? $person_array[$this->pension['pension_person']] : '请选择';?><i class="select-arrow"></i></a>
                                    <div class="select-list" style="display: none">
                                        <ul class="class-box">
                                            <li field_value="" field_key="pension_person">请选择</li>
                                            <li field_value="1" field_key="pension_person">自理</li>
                                            <li field_value="2" field_key="pension_person">半自理</li>
                                            <li field_value="3" field_key="pension_person">全自理</li>
                                            <li field_value="4" field_key="pension_person">特护</li>
                                        </ul>
                                    </div>
                                </div>
                                <input type="hidden" id="pension_person" name="pension_person" value="<?php echo $this->pension['pension_person'];?>">
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
							<label>床位数量：</label>
							<div class="form-item-value">
								<input type="text" id="pension_bed" name="pension_bed" value="<?php echo $this->pension['pension_bed'];?>" class="form-input" placeholder="输入床位数量">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>占地面积：</label>
							<div class="form-item-value">
								<input type="text" id="pension_cover" name="pension_cover" value="<?php echo $this->pension['pension_cover'];?>" class="form-input" placeholder="输入占地面积"> 万平方米
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>收费标准：</label>
							<div class="form-item-value">
								<input type="text" id="pension_price" name="pension_price" value="<?php echo $this->pension['pension_price'];?>" class="form-input" placeholder="输入收费标准">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>联系电话：</label>
							<div class="form-item-value">
								<input type="text" id="pension_phone" name="pension_phone" value="<?php echo $this->pension['pension_phone'];?>" class="form-input" placeholder="输入联系电话">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>所在地区：</label>
							<div class="form-item-value">
								<div class="first-province-box" prefix="pension" style="display:inline-block">
									<div class="select-class">
										<a href="javascript:;" class="select-choice"><?php echo !empty($pension_provincename) ? $pension_provincename : '-省份-';?><i class="select-arrow"></i></a>
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
									<?php if(!empty($pension_city_list)) { ?>
									<div class="select-class">
										<a href="javascript:;" class="select-choice"><?php echo !empty($pension_cityname) ? $pension_cityname : '-城市-';?><i class="select-arrow"></i></a>
										<div class="select-list" style="display: none">
											<ul>
												<li field_value="">-城市-</li>
												<?php foreach($pension_city_list as $key => $value) { ?>
												<li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
									<?php } ?>
								</div>
								<div class="first-area-box" style="display:inline-block">
									<?php if(!empty($pension_area_list)) { ?>
									<div class="select-class">
										<a href="javascript:;" class="select-choice"><?php echo !empty($pension_areaname) ? $pension_areaname : '-州县-';?><i class="select-arrow"></i></a>
										<div class="select-list" style="display: none">
											<ul>
												<li field_value="">-州县-</li>
												<?php foreach($pension_area_list as $key => $value) { ?>
												<li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
									<?php } ?>
								</div>
								<input type="hidden" id="pension_provinceid" name="pension_provinceid" value="<?php echo $pension_provinceid;?>" />
								<input type="hidden" id="pension_cityid" name="pension_cityid" value="<?php echo $pension_cityid;?>" />
								<input type="hidden" id="pension_areaid" name="pension_areaid" value="<?php echo $pension_areaid;?>" />
								<div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>详细地址：</label>
							<div class="form-item-value">
								<input type="text" id="pension_address" name="pension_address" class="form-input w-400" value="<?php echo $this->pension['pension_address'];?>">
								<div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
                            <label>机构概述：</label>
                            <div class="form-item-value">
                                <textarea class="form-textarea w-10-9" id="pension_summary" name="pension_summary" rows="10"><?php echo $this->pension['pension_summary'];?></textarea>
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
	<script type="text/javascript" src="templates/js/profile/pension_profile.js"></script>
<?php include(template('common_footer'));?>