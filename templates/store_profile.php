<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>商家中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=store_order">订单管理</a></li>
						<li><a href="index.php?act=store_return">退换货</a></li>
						<li><a href="index.php?act=store_order&state=payment">待发货</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=store_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">商品中心</h3>
					<ul>
						<li><a href="index.php?act=store_goods&op=add">发布商品</a></li>
						<li><a href="index.php?act=store_goods">出售商品</a></li>
						<li><a href="index.php?act=store_goods&op=goods_unshow">仓库商品</a></li>
						<li><a href="index.php?act=store_spec">规格管理</a></li>
					</ul>
					<h3 class="no4">营销中心</h3>
					<ul>
						<li><a href="index.php?act=store_coupon">优惠券管理</a></li>
					</ul>
					<h3 class="no5">商家设置</h3>
					<ul>
						<li><a class="active" href="index.php?act=store_profile">商家信息</a></li>
						
						<li><a href="index.php?act=store_transport">运费模板</a></li>
						<li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
            	<div class="center-title clearfix">
					<strong>商家信息</strong>
				</div>
				<div class="user-info">
					<div class="form-list">
						<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                        <div class="form-item clearfix">
							<label>店铺名称：</label>
							<div class="form-item-value">
								<input type="text" id="store_name" name="store_name" value="<?php echo $this->store['store_name'];?>" class="form-input" placeholder="输入你的店铺名称">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>店铺LOGO：</label>
							<div class="form-item-value">
								<div class="picture-list">
									<ul class="clearfix">
										<?php if(!empty($this->store['store_logo'])) { ?>
										<li id="show_image_0" class="cover-item">
											<img src="<?php echo $this->store['store_logo'];?>">
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
										<input type="hidden" id="store_logo" name="store_logo" class="image_0" value="<?php echo $this->store['store_logo'];?>"  />
										<div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
									</ul>
								</div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>店铺横幅：</label>
							<div class="form-item-value">
								<div class="picture-list">
									<ul class="clearfix">
										<?php if(!empty($this->store['store_banner'])) { ?>
										<li id="show_image_1" class="cover-item">
											<img src="<?php echo $this->store['store_banner'];?>">
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
										<input type="hidden" id="store_banner" name="store_banner" class="image_1" value="<?php echo $this->store['store_banner'];?>"  />
										<div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
									</ul>
								</div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>所在地区：</label>
							<div class="form-item-value">
								<div class="first-province-box" prefix="store" style="display:inline-block">
									<div class="select-class">
										<a href="javascript:;" class="select-choice"><?php echo !empty($store_provincename) ? $store_provincename : '-省份-';?><i class="select-arrow"></i></a>
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
									<?php if(!empty($store_city_list)) { ?>
									<div class="select-class">
										<a href="javascript:;" class="select-choice"><?php echo !empty($store_cityname) ? $store_cityname : '-城市-';?><i class="select-arrow"></i></a>
										<div class="select-list" style="display: none">
											<ul>
												<li field_value="">-城市-</li>
												<?php foreach($store_city_list as $key => $value) { ?>
												<li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
									<?php } ?>
								</div>
								<div class="first-area-box" style="display:inline-block">
									<?php if(!empty($store_area_list)) { ?>
									<div class="select-class">
										<a href="javascript:;" class="select-choice"><?php echo !empty($store_areaname) ? $store_areaname : '-州县-';?><i class="select-arrow"></i></a>
										<div class="select-list" style="display: none">
											<ul>
												<li field_value="">-州县-</li>
												<?php foreach($store_area_list as $key => $value) { ?>
												<li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
									<?php } ?>
								</div>
								<input type="hidden" id="store_provinceid" name="store_provinceid" value="<?php echo $store_provinceid;?>" />
								<input type="hidden" id="store_cityid" name="store_cityid" value="<?php echo $store_cityid;?>" />
								<input type="hidden" id="store_areaid" name="store_areaid" value="<?php echo $store_areaid;?>" />
								<div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>详细地址：</label>
							<div class="form-item-value">
								<input type="text" id="store_address" name="store_address" class="form-input w-400" value="<?php echo $this->store['store_address'];?>">
								<div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>主营业务：</label>
							<div class="form-item-value">
                            	<textarea id="store_content" name="store_content" class="form-textarea w-10-9" rows="5"><?php echo $this->store['store_content'];?></textarea>
                    			<div class="Validform-checktip Validform-wrong"></div>
                            </div>
						</div>
                        <div class="form-item clearfix">
							<label>QQ：</label>
							<div class="form-item-value">
								<input type="text" id="store_qq" name="store_qq" value="<?php echo $this->store['store_qq'];?>" class="form-input" placeholder="输入你的店铺QQ">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>阿里旺旺：</label>
							<div class="form-item-value">
								<input type="text" id="store_ww" name="store_ww" value="<?php echo $this->store['store_ww'];?>" class="form-input" placeholder="输入你的阿里旺旺">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>联系电话：</label>
							<div class="form-item-value">
								<input type="text" id="store_phone" name="store_phone" value="<?php echo $this->store['store_phone'];?>" class="form-input" placeholder="输入你的联系电话">
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
		var file_name = 'store';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>   	
    <script type="text/javascript" src="templates/js/imageupload.js"></script>
	<script type="text/javascript" src="templates/js/profile/store_profile.js"></script>
<?php include(template('common_footer'));?>