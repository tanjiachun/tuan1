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
						<li><a class="active" href="index.php?act=store_goods&op=add">发布商品</a></li>
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
						<li><a href="index.php?act=store_profile">商家信息</a></li>
						<li><a href="index.php?act=store_transport">运费模板</a></li>
                        <li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>发布商品</strong>
				</div>
				<div class="edit-box">
					<div class="edit-body">
						<form method="post" id="goods_form" action="index.php?act=store_goods&op=add">
							<div class="edit-body-title">基础信信息</div>
							<div class="edit-body-con">
								<div class="form-list">
									<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
									<input type="hidden" id="form_submit" name="form_submit" value="ok" />
									<div class="form-item clearfix">
										<label>商品名称：</label>
										<div class="form-item-value">
											<input type="text" id="goods_name" name="goods_name" class="form-input w-600" value="">
											<div class="Validform-checktip Validform-wrong"></div>
											<div class="t-tips">商品标题名称长度至少3个字符，最长50个汉字</div>
										</div>
									</div>
									<div class="form-item clearfix">
										<label>商品类别：</label>
										<div class="form-item-value">
											<div class="select-class class-box">
												<a href="javascript:;" class="select-choice">请选择<i class="select-arrow"></i></a>
												<div class="select-list" style="display: none">
													<ul>
														<li field_value="" field_key="class_id">请选择</li>
														<?php foreach($class_list as $key => $value) { ?>
														<li field_value="<?php echo $value['class_id'];?>" field_key="class_id"><?php echo $value['class_name'];?></li>
														<?php } ?>
													</ul>
												</div>
											</div>
											<input type="hidden" id="class_id" name="class_id" value="">
											<div class="Validform-checktip Validform-wrong"></div>
										</div>
									</div>
									<div class="form-item clearfix">
										<label>规格值：</label>
										<div class="form-item-value spec-list">
											<?php if(empty($spec_list)) { ?>
											<a href="index.php?act=store_spec" class="btn">添加规格项目</a>
											<?php } else { ?>
											<?php foreach($spec_list as $key => $value) { ?>
											<span class="check" sp_id="<?php echo $value['sp_id'];?>"><i class="iconfont icon-type"></i><?php echo $value['sp_name'];?></span>
											<?php } ?>
											<?php } ?>
										</div>
									</div>
									<div class="spec-box"></div>
									<div class="form-item clearfix">
										<label>商品价格：</label>
										<div class="form-item-value">
											<input type="hidden" id="goods_price_interval" name="goods_price_interval" value="">
											<input type="text" id="goods_price" name="goods_price" class="form-input w-100" value=""> 元
											<div class="Validform-checktip Validform-wrong"></div>
											<div class="t-tips">价格必须是0.01~9999999之间的数字，且不能高于市场价。此价格为商品实际销售价格，如果商品存在规格，该价格显示最低价格。</div>
										</div>
									</div>
                                    <div class="form-item clearfix">
										<label>商品库存：</label>
										<div class="form-item-value">
											<input type="text" id="goods_storage" name="goods_storage" class="form-input w-100" value="">
											<div class="Validform-checktip Validform-wrong"></div>
											<div class="t-tips">商铺库存数量必须为1~999999999之间的整数。若启用了库存配置，则系统自动计算商品的总数，此处无需卖家填写。</div>
										</div>
									</div>
									<div class="form-item clearfix">
										<label>商家货号：</label>
										<div class="form-item-value">
											<input type="text" id="goods_serial" name="goods_serial" class="form-input w-100" value="">
											<div class="t-tips">商家货号是指商家管理商品的编号，买家不可见。最多可输入20个字符，支持输入中文、字母、数字、_、/、-和小数点</div>
										</div>
									</div>
									<div class="form-item clearfix">
										<label>促销类型：</label>
										<div class="form-item-value promotion-box">
											<span class="radio active" field_value="normal" field_key="goods_promotion_type"><i class="iconfont icon-type"></i>普通商品</span>
											<span class="radio" field_value="cheap" field_key="goods_promotion_type"><i class="iconfont icon-type"></i>商品特惠</span>
											<span class="radio" field_value="group" field_key="goods_promotion_type"><i class="iconfont icon-type"></i>商品团购</span>
                                        </div>
										<input type="hidden" id="goods_promotion_type" name="goods_promotion_type" value="normal">
									</div>	
									<div class="form-item clearfix" id="normal" style="display: none">
										<label>&nbsp;</label> 
										<div class="form-item-value">  
											<div id="cheap" style="display: none">
												商品原价：<input type="text" id="goods_original_price" name="goods_original_price" class="form-input w-100" value=""> 元
											</div>
											<div id="group" style="display: none">
												团购人数：<input type="text" id="goods_group_number" name="goods_group_number" class="form-input w-100" value=""> 人&nbsp;&nbsp;
												团购价格：<input type="text" id="goods_group_price" name="goods_group_price" class="form-input w-100" value=""> 元
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="edit-body-title">商品详情描述</div>
							<div class="edit-body-con">
								<div class="form-list">
                                	<div class="form-item clearfix">
                                        <label>商品封面：</label>
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
                                                    <input type="hidden" id="goods_image" name="goods_image" class="image_0" value=""  />
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
									<div class="form-item clearfix">
										<label>商品图片：</label>
										<div class="form-item-value">
                                            <div class="picture-list">
                                                <ul class="clearfix">
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
										<label>商品描述：</label>
										<div class="form-item-value">
											<?php showeditor('goods_body', "", "100%", "400px", $style = "visibility:hidden;", "true", false);?>
										</div>
									</div>
								</div>
							</div>
                            <div class="attr-body attr-box"></div>
							<div class="edit-body-title">商品物流信息</div>
							<div class="edit-body-con">
								<div class="form-list">
									<div class="form-item clearfix">
										<label>运费：</label>
										<div class="form-item-value transport-list">
											<span class="radio active" field_value="freight" field_key="goods_postage"><i class="iconfont icon-type"></i>固定运费</span>
											<span class="radio" field_value="transport" field_key="goods_postage"><i class="iconfont icon-type"></i>使用运费模板</span>
                                        </div>
										<input type="hidden" id="goods_postage" name="goods_postage" value="freight">
									</div>	
									<div class="form-item clearfix">
										<label>&nbsp;</label> 
										<div class="form-item-value">  
											<div id="freight">
												<?php echo $this->store['kd_rename'];?>：<input type="text" id="kd_price" name="kd_price" class="form-input w-100" value=""> 元&nbsp;&nbsp;
												<?php echo $this->store['es_rename'];?>：<input type="text" id="es_price" name="es_price" class="form-input w-100" value=""> 元&nbsp;&nbsp;
												<?php echo $this->store['py_rename'];?>：<input type="text" id="py_price" name="py_price" class="form-input w-100" value=""> 元
											</div>
											<div id="transport" style="display: none">
												<?php if(empty($transport_list)) { ?>
												<a href="index.php?act=store_transport" class="btn">添加运费模板</a>
												<?php } else { ?>
												<div class="select-class transport-box">
													<a href="javascript:;" class="select-choice">请选择<i class="select-arrow"></i></a>
													<div class="select-list" style="display: none">
														<ul>
															<li field_value="" field_key="transport_id">请选择</li>
															<?php foreach($transport_list as $key => $value) { ?>
															<li field_value="<?php echo $value['transport_id'];?>" field_key="transport_id"><?php echo $value['transport_name'];?></li>
															<?php } ?>
														</ul>
													</div>
												</div>
												<?php } ?>
												<input type="hidden" id="transport_id" name="transport_id" value="">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="edit-body-con">
								<div class="form-list">
									<div class="form-item clearfix">
										<label>&nbsp;</label>
										<div class="form-item-value">
											<a href="javascript:checksubmit();" class="btn btn-primary">发布</a>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var file_name = 'goods';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="templates/js/profile/store_goods.js"></script>    
	<script type="text/javascript" src="templates/js/imageupload.js"></script>
<?php include(template('common_footer'));?>